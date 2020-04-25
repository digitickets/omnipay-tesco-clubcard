<?php

namespace DigiTickets\TescoClubcard\UkMessages\Voucher;

use DigiTickets\TescoClubcard\Responses\Interfaces\RedeemResponseInterface;
use DigiTickets\TescoClubcard\Messages\Uk\Common\AbstractVoucherResponse;

class RedeemResponse extends AbstractVoucherResponse implements RedeemResponseInterface
{
    /**
     * @return string
     */
    protected function getSuccessStatusCode()
    {
        return self::STATUS_REDEEMED;
    }
}
