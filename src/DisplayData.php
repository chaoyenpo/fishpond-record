<?php

namespace Gamesmkt\FishpondRecord;

use Gamesmkt\FishpondRecord\BetRecord;
use Gamesmkt\FishpondRecord\DisplayDataInterface;

class DisplayData implements DisplayDataInterface
{
    public $betRecord;

    public function __construct(BetRecord $betRecord)
    {
        $this->betRecord = $betRecord;
    }

    public function toArray(): array
    {
        return [
            [
                'Name' => 'bet_id',
                'Value' => $this->betRecord->getId(),
            ],
        ];
    }
}
