<?php

namespace Gamesmkt\FishpondRecord;

use Gamesmkt\FishpondRecord\CanNormalizeBetRecord;
use Gamesmkt\Fishpond\Plugin\AbstractPlugin;

class NormalizeBetRecordPlugin extends AbstractPlugin
{
    /**
     * Get the method name.
     *
     * @return string
     */
    public function getMethod()
    {
        return 'normalizeBetRecord';
    }

    /**
     * Assert support normalize bet record and run it.
     *
     * @param \Gamesmkt\Fishpond\RecordInterface[] $records
     * @param array $config
     *
     * @throws \Gamesmkt\Fishpond\Exception\NormalizeBetRecordException
     *
     * @return array
     */
    public function handle(array $betRecords, array $config = [])
    {
        $config = $this->fishpond::ensureConfig($config);

        if (!$this->fishpond->getAdapter() instanceof CanNormalizeBetRecord) {
            return $betRecords;
        }

        $betRecordMethod = $this->fishpond->getAdapter()->getBetRecordMethod($config);

        $normalizeBetRecords = array_map(function ($betRecord) use ($betRecordMethod) {
            return $betRecordMethod->normalize($betRecord);
        }, $betRecords);

        if (!isset($normalizeBetRecords)) {
            throw new NormalizeBetRecordException(
                get_class($this->fishpond->getAdapter()) . ' normalize error.'
            );
        }

        return $normalizeBetRecords;
    }

}
