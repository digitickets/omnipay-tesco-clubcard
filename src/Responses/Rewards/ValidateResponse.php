<?php

namespace DigiTickets\Integration\TescoClubcard\Api\Responses\Rewards;

use DigiTickets\Integration\TescoClubcard\Api\Responses\Interfaces\ValidateResponseInterface;

class ValidateResponse extends AbstractResponse implements ValidateResponseInterface
{
    protected function getSuccessStatusCode(): string
    {
        return self::STATUS_ACTIVE;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage()
    {
        // @TODO: extract the error message out of the response.
        return 'TBC';
    }

    public function getValue(): float
    {
        // @TODO: Finish this.
        return -999;
    }
}
