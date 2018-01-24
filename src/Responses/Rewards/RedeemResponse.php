<?php

namespace DigiTickets\TescoClubcard\Responses\Rewards;

use DigiTickets\TescoClubcard\Responses\Interfaces\RedeemResponseInterface;

class RedeemResponse extends AbstractResponse implements RedeemResponseInterface
{
    protected function getSuccessStatusCode(): string
    {
        return ''; // @TODO: Finish this.
    }

    public function success(): bool
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
