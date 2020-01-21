<?php

namespace Gamesmkt\FishpondRecord;

use Gamesmkt\Fishpond\Config;

trait BetRecordMethodTrait
{
    public function getBetRecordMethod(Config $config)
    {
        $class = str_replace('Adapter', 'BetRecordMethod', __CLASS__);

        return new $class($config);
    }
}
