<?php

namespace Gamesmkt\FishpondRecord;

use Gamesmkt\Fishpond\BetRecord;
use Gamesmkt\Fishpond\BetRecordFormatInterface;
use Gamesmkt\Fishpond\BetRecordInterface;
use Gamesmkt\Fishpond\Config;
use Gamesmkt\Fishpond\RecordInterface;

class BetRecordParser
{
    /** @var Config */
    protected $config;

    /** @var BetRecordFormatInterface */
    protected $format;

    /** @var Record[] */
    protected $records;

    public function __construct(BetRecordFormatInterface $format, array $records, Config $config)
    {
        $this->config = $config;
        $this->format = $format;
        $this->records = $records;
    }

    public function execute(): array
    {
        $formatRecords = [];

        foreach ($this->records as $record) {
            $formatRecords[] = (array) $this->toModel($record);
        };

        return $formatRecords;
    }

    public function toModel($sourceRecord): RecordInterface
    {
        $record = new BetRecord();

        // 儲存原始注單資料
        $record->source = $sourceRecord;

        $record->playerName = $this->format->getPlayerName($sourceRecord);

        // 遊戲標識符，此專案產生的。
        $record->gameId = (string) $this->format->getGameId($sourceRecord);

        // 注單標識符，每筆注單只會有一個。
        $record->id = (string) data_get($sourceRecord, $this->format->parameter[BetRecordFormatInterface::NAME_BET_ID]);

        // 局號標識符，一局多單的關聯號，每局比賽會有一個，例如百家樂開局會有個局號。
        $record->roundId = isset($this->format->parameter[BetRecordFormatInterface::NAME_ROUND_ID])
        ? (string) data_get($sourceRecord, $this->format->parameter[BetRecordFormatInterface::NAME_ROUND_ID])
        : '';

        // 注單狀態，紀錄這個注單的狀態，未結算或是已結算等等的。
        $record->status = $this->format->getStatus($sourceRecord);

        // 注單建立時間。
        $record->createAt = $this->format->formatDateTime(
            data_get($sourceRecord, $this->format->parameter[BetRecordFormatInterface::NAME_CREATED_AT])
        );

        // 注單更新時間，電子機率類的通常會一樣。
        $record->updateAt = $record->status === BetRecordInterface::STATUS_ACTIVE
        ? ''
        : $this->format->formatDateTime(
            data_get($sourceRecord, $this->format->parameter[BetRecordFormatInterface::NAME_UPDATED_AT])
        );

        // 投注金額
        $record->betAmount = $this->format->getBetAmount($sourceRecord);

        // 有效投注金額
        $record->validBetAmount = $this->validBetAmount($sourceRecord);

        // 派彩金額
        $record->payment = $record->status === BetRecordInterface::STATUS_ACTIVE
        ? ''
        : $this->payment($sourceRecord);

        // TODO: 這邏輯是 Mew 專屬的
        // // 平台的使用者標識符，取得 Player 後取得。
        // $record->game_merchant_user_id = $this->format->getPlayer($betRecord)->game_merchant_user_id;
        // // Adapter 標識符，從 GameMerchant 取得。
        // $record->adapter_id = $this->gameMerchant->adapter_id;
        // // 顯示資料，會在平台前端顯示的注單詳細資料
        // $record->display_data = $this->format->getDisplayData($record)->toArray();

        return $record;
    }

    protected function payment($betRecord): string
    {
        $paymentAmount = $this->format->getPayment($betRecord);
        $winlossAmount = $this->format->getWinloss($betRecord);
        $betAmount = $this->format->getBetAmount($betRecord);
        $validBetAmount = $this->format->getValidBetAmount($betRecord);

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

    protected function validBetAmount($betRecord): string
    {
        if ($this->format->getStatus($betRecord) === BetRecordInterface::STATUS_ACTIVE) {
            return '';
        }

        $betAmount = $this->format->getBetAmount($betRecord);
        $validBetAmount = $this->format->getValidBetAmount($betRecord);

        if ($validBetAmount > $betAmount) {
            return $betAmount;
        }
        return $validBetAmount;
    }
}
