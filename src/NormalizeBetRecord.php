<?php

namespace Gamesmkt\FishpondRecord;

use Gamesmkt\Fishpond\Adapter\CanNormalizeBetRecord;
use Gamesmkt\Fishpond\Plugin\AbstractPlugin;

class NormalizeBetRecord extends AbstractPlugin
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
    public function handle(array $records, array $config = [])
    {
        $config = $this->fishpond::ensureConfig($config);

        if (!$this->fishpond->getAdapter() instanceof CanNormalizeBetRecord) {
            return $records;
        }

        $records = $this->fishpond->getAdapter()->normalizeBetRecord($records, $config);

        if (!isset($records)) {
            throw new NormalizeBetRecordException(
                get_class($this->fishpond->getAdapter()) . ' normalize error.'
            );
        }

        return $records;
    }

}
