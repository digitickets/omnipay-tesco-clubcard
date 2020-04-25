<?php

namespace DigiTickets\TescoClubcard\Messages\Uk\Omnipay;

use DigiTickets\TescoClubcard\Messages\Uk\Common\AbstractVoucherResponse;

class PurchaseResponse extends AbstractVoucherResponse
{
    /**
     * @return string
     */
    protected function getSuccessStatusCode()
    {
        return self::STATUS_REDEEMED;
    }
}