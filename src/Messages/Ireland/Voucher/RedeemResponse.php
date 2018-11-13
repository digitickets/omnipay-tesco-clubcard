<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Voucher;

use DigiTickets\TescoClubcard\Messages\Interfaces\RedeemResponseInterface;

class RedeemResponse extends AbstractResponse implements RedeemResponseInterface
{
    /**
     * @return string
     */
    protected function getSuccessStatusCode()
    {
        return self::STATUS_REDEEMED;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage()
    {
        // @TODO: extract the error message out of the response.
        return 'TBC (redeem response)';
    }
}
