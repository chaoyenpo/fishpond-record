<?php

namespace Gamesmkt\FishpondRecord;

class BetRecord implements BetRecordInterface
{
    public function getId()
    {
        return $this->id;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function getBetAmount()
    {
        return $this->betAmount;
    }

    public function getValidBetAmount()
    {
        return $this->validBetAmount;
    }

    public function getPayment()
    {
        return $this->payment;
    }

    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }
}
