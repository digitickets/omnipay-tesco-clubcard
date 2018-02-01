<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Responses;

use DigiTickets\TescoClubcard\Messages\Interfaces\UnredeemResponseInterface;

class UnredeemResponse extends AbstractResponse implements UnredeemResponseInterface
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
        return 'The voucher could not be unredeemed';
    }
}
