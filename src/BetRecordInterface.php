<?php

namespace Gamesmkt\FishpondRecord;

use Gamesmkt\Fishpond\RecordInterface;

interface BetRecordInterface extends RecordInterface
{
    /**
     * 0 = 未投注成功的注单
     * 1 = 确认中
     */
    const STATUS_FAILED = 0;
    const STATUS_TO_BE_DETERMINED = 1;

    /**
     * 2 = 已确认 (已扣款)
     * 3 = 已结算，通知商户并成功得到返回的注单
     */
    const STATUS_ACTIVE = 2;
    const STATUS_COMPLETED = 3;

    /**
     * 4 = 已撤单（投注成功，还未结算被取消的注单）
     * 5 = 已退单（已经结算，被要求取消成功的注单）
     */
    const STATUS_CANCEL = 4;
    const STATUS_REJECTED = 5;

    /**
     * 6 = 异常扣款（因网络等原因无法确认注单是否扣款成功，默认当作撤单处理，等待系统恢复后由商户判断，若已扣款则应当补款给玩家，若无扣款则无需操作）
     * 7 = 异常补款(主动请求撤单中，即投注已确认扣款，还未结算，因为特殊原因需要将此注单撤消，而因网络等原因无法确认商户是否已经补款给玩家的异常注单)
     * 8 = 已结算，但通知商户返回失败的注单，无法确认商户是否已对用户进行结算
     * 9 = 已提前结算成功的注单
     * 10 = 未知狀態注單
     */
    const STATUS_ERROR_WITHDRAW = 6;
    const STATUS_ERROR_DEPOSIT = 7;
    const STATUS_COMPLETED_ROLLBACK = 8;
    const STATUS_ADVANCE_COMPLETED = 9;
    const STATUS_UNKNOWN = 10;
}
