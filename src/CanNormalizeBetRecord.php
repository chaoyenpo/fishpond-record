<?php

namespace Gamesmkt\FishpondRecord;

use Gamesmkt\Fishpond\Config;

/**
 * 實現這個介面的 Adapter 讓 Fishpond 知道 Bet Record 可以被正規化。
 */
interface CanNormalizeBetRecord
{
    /**
     * 正規化下注紀錄。
     *
     * @param \Gamesmkt\Fishpond\RecordInterface[] $records
     * @param \Gamesmkt\Fishpond\Config $config
     *
     * @throws \Gamesmkt\Fishpond\Exception\NormalizeBetRecordException
     *
     * @return array
     */
    public function normalizeBetRecord(array $records, Config $config);
}
