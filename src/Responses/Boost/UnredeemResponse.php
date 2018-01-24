<?php

namespace DigiTickets\TescoClubcard\Responses\Boost;

use DigiTickets\TescoClubcard\Responses\Interfaces\UnredeemResponseInterface;

class UnredeemResponse extends AbstractResponse implements UnredeemResponseInterface
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
}
