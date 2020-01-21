<?php

namespace Gamesmkt\FishpondRecord;

use Gamesmkt\Fishpond\Config;

/**
 * 實現這個介面的 Adapter 讓 Fishpond 知道 Bet Record 可以被正規化。
 */
interface CanNormalizeBetRecord
{
    /**
     * 取得下注紀錄模板方法。
     *
     * @param \Gamesmkt\Fishpond\Config $config
     *
     * @return \Gamesmkt\FishpondRecord\Template\AbstractBetRecord
     */
    public function getBetRecordMethod(Config $config);
}
