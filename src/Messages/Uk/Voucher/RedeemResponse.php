<?php

namespace DigiTickets\TescoClubcard\Messages\Uk\Voucher;

use DigiTickets\TescoClubcard\Messages\Uk\Common\AbstractVoucherResponse;

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
