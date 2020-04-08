<?php

namespace DigiTickets\TescoClubcard\UkMessages\Voucher;

use DigiTickets\TescoClubcard\Responses\Interfaces\RedeemResponseInterface;
use DigiTickets\TescoClubcard\Messages\Uk\Common\AbstractVoucherResponse;

class RedeemResponse extends AbstractVoucherResponse implements RedeemResponseInterface
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
    public function isSuccessful()
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
