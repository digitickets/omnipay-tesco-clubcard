<?php

namespace DigiTickets\TescoClubcard\Messages\Uk\Voucher;

use DigiTickets\TescoClubcard\Responses\Interfaces\UnredeemResponseInterface;
use DigiTickets\TescoClubcard\Messages\Uk\Common\AbstractVoucherResponse;

class UnredeemResponse extends AbstractVoucherResponse implements UnredeemResponseInterface
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
