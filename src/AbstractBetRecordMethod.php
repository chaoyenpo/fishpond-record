<?php

namespace Gamesmkt\FishpondRecord;

use Carbon\Carbon;
use Gamesmkt\FishpondRecord\BetRecord;
use Gamesmkt\FishpondRecord\BetRecordInterface;
use Gamesmkt\Fishpond\Config;

abstract class AbstractBetRecordMethod
{
    protected $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function normalize($sourceBetRecord): BetRecord
    {
        $betRecord = new BetRecord();

        // 玩家名稱
        $betRecord->playerName = $this->getPlayerName($sourceBetRecord);

        // 遊戲標識符，此專案產生的。
        $betRecord->gameId = (string) $this->getGameId($sourceBetRecord);

        // 注單標識符，每筆注單只會有一個。
        $betRecord->id = (string) $this->getBetId($sourceBetRecord);

        // 局號標識符，一局多單的關聯號，每局比賽會有一個，例如百家樂開局會有個局號。
        $betRecord->roundId = (string) $this->getRoundId($sourceBetRecord);

        // 注單狀態，紀錄這個注單的狀態，未結算或是已結算等等的。
        $betRecord->status = $this->getStatus($sourceBetRecord);

        // 注單建立時間。
        $betRecord->createdAt = $this->formatDateTime($this->getCreatedAt($sourceBetRecord));

        // 注單更新時間，電子機率類的通常會一樣。
        $betRecord->updatedAt = $this->getStatus($sourceBetRecord) === BetRecordInterface::STATUS_ACTIVE
        ? ''
        : $this->formatDateTime($this->getUpdatedAt($sourceBetRecord));

        // 投注金額
        $betRecord->betAmount = $this->getBetAmount($sourceBetRecord);

        // 有效投注金額
        $betRecord->validBetAmount = $this->calculateValidBetAmount($sourceBetRecord);

        // 派彩金額
        $betRecord->payment = $this->getStatus($sourceBetRecord) === BetRecordInterface::STATUS_ACTIVE
        ? ''
        : $this->calculatePayment($sourceBetRecord);

        // 顯示資料
        $betRecord->displayData = $this->getDisplayData($betRecord);

        return $betRecord;
    }

    abstract public function getCreatedAt($betRecord): string;

    abstract public function getUpdatedAt($betRecord): string;

    abstract public function getBetId($betRecord): string;

    abstract public function getRoundId($betRecord): string;

    abstract public function getPlayerName($betRecord): string;

    abstract public function getGameId($betRecord): string;

    abstract public function getStatus($betRecord): int;

    abstract public function getBetAmount($betRecord): string;

    abstract public function getValidBetAmount($betRecord): string;

    abstract public function getPayment($betRecord): ?string;

    abstract public function getWinloss($betRecord): ?string;

    abstract public function formatDateTime($betRecord): ?Carbon;

    abstract public function getDisplayData($betRecord): array;

    protected function calculatePayment($betRecord): string
    {
        $paymentAmount = $this->getPayment($betRecord);
        $winlossAmount = $this->getWinloss($betRecord);
        $betAmount = $this->getBetAmount($betRecord);
        $validBetAmount = $this->getValidBetAmount($betRecord);

        if (isset($paymentAmount)) {
            return $paymentAmount;
        }

        if ($validBetAmount === '0') {
            return "0";
        }

        if ($validBetAmount >= $betAmount) {
            return bcadd($winlossAmount, $betAmount, 6);
        }

        return bcadd($winlossAmount, $validBetAmount, 6);
    }

    protected function calculateValidBetAmount($betRecord): string
    {
        if ($this->getStatus($betRecord) === BetRecordInterface::STATUS_ACTIVE) {
            return '';
        }

        $betAmount = $this->getBetAmount($betRecord);
        $validBetAmount = $this->getValidBetAmount($betRecord);

        if ($validBetAmount > $betAmount) {
            return $betAmount;
        }
        return $validBetAmount;
    }
}
