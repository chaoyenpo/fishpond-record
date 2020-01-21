<?php

namespace Gamesmkt\FishpondRecord;

class BetRecord implements BetRecordInterface
{
    /** @var string */
    public $id;

    /** @var string */
    public $roundId;

    /**
     * @param string $id The record id
     * @param string $id The round id
     */
    public function __construct(string $id = null, string $roundId = null)
    {
        $this->id = $id;
        $this->roundId = $roundId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRoundId()
    {
        return $this->roundId;
    }

    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }
}
