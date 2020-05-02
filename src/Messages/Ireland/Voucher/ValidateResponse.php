<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Voucher;

use DigiTickets\TescoClubcard\Messages\Ireland\Common\AbstractVoucherResponse;

class ValidateResponse extends AbstractVoucherResponse
{
    /**
     * @return string
     */
    protected function getSuccessStatusCode()
    {
        return self::STATUS_ACTIVE;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return ((int) $this->get('ValueString')) / 100;
    }

    public function getProductType()
    {
        return $this->get('ProductType');
    }
}
