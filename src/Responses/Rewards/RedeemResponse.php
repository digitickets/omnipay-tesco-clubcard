<?php

namespace DigiTickets\TescoClubcard\Responses\Rewards;

use DigiTickets\TescoClubcard\Responses\Interfaces\RedeemResponseInterface;

class RedeemResponse extends AbstractResponse implements RedeemResponseInterface
{
    /**
     * @return string
     */
    protected function getSuccessStatusCode()
    {
        return ''; // @TODO: Finish this.
    }

    /**
     * @return bool
     */
    public function success()
    {
        return false; // @TODO: Needs finishing
    }

    /**
     * @return string|null
     */
    public function getErrorMessage()
    {
        return 'Has not been implemented yet';
    }
}
