<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Responses;

use DigiTickets\TescoClubcard\Messages\Interfaces\RedeemResponseInterface;

class RedeemResponse extends AbstractResponse implements RedeemResponseInterface
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
        return 'TBC';
    }
}
