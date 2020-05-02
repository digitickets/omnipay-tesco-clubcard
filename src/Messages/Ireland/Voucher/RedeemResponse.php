<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Voucher;

use DigiTickets\TescoClubcard\Messages\Ireland\Common\AbstractVoucherResponse;

class RedeemResponse extends AbstractVoucherResponse
{
    /**
     * @return string
     */
    protected function getSuccessStatusCode()
    {
        return self::STATUS_REDEEMED;
    }
}
