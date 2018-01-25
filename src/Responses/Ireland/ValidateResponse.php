<?php

namespace DigiTickets\TescoClubcard\Responses\Ireland;

use DigiTickets\TescoClubcard\Responses\Interfaces\ValidateResponseInterface;

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
        ];
        $error = isset($map[$this->get('Status')]) ? $map[$this->get('Status')] : 'Unknown error [Ireland ValidateResponse]';

        return $error;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return ((int) $this->get('ValueString')) / 100;
    }
}
