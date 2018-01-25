<?php

namespace DigiTickets\TescoClubcard\Uk\Responses;

use DigiTickets\TescoClubcard\Responses\Interfaces\UnredeemResponseInterface;

class UnredeemResponse extends AbstractResponse implements UnredeemResponseInterface
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
