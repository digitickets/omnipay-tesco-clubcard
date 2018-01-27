<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Responses;

use DigiTickets\TescoClubcard\Messages\Interfaces\ValidateResponseInterface;

class ValidateResponse extends AbstractResponse implements ValidateResponseInterface
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
        // Map the status to an error message.
        $map = [
            'NotFound' => 'Voucher was not found',
            'Redeemed' => 'Voucher is already redeemed',
        ];
        $error = isset($map[$this->get('Status')])
            ?
            $map[$this->get('Status')]
            :
            'Unknown error [Ireland ValidateResponse]';

        return $error;
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
