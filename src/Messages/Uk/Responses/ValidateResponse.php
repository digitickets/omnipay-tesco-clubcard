<?php

namespace DigiTickets\TescoClubcard\Uk\Responses;

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
