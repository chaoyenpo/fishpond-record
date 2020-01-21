<?php

namespace App\GameService\DisplayData;

use Gamesmkt\FishpondRecord\DisplayDataInterface;

abstract class DisplayDataDecorator implements DisplayDataInterface
{
    protected $displayData;

    protected $betRecord;

    public function __construct(DisplayDataContract $displayData)
    {
        $this->displayData = $displayData;

        $this->betRecord = $this->displayData->betRecord;
    }
}
