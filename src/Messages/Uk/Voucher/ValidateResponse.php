<?php

namespace DigiTickets\TescoClubcard\Messages\Uk\Voucher;

use DigiTickets\TescoClubcard\Messages\Interfaces\ValidateResponseInterface;
use DigiTickets\TescoClubcard\Messages\Uk\Common\AbstractVoucherResponse;

class ValidateResponse extends AbstractVoucherResponse implements ValidateResponseInterface
{
    /**
     * @return string
     */
    protected function getSuccessStatusCode()
    {
        return self::STATUS_ACTIVE;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage()
    {
        // @TODO: extract the error message out of the response.
        return 'TBC (validate response)';
    }

    /**
     * @return float
     */
    public function getValue()
    {
        // @TODO: Finish this.
        return -999;
    }
}
