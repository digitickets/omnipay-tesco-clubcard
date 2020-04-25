<?php

namespace DigiTickets\TescoClubcard\Messages\Uk\Voucher;

use DigiTickets\TescoClubcard\Messages\Interfaces\UnredeemResponseInterface;
use DigiTickets\TescoClubcard\Messages\Uk\Common\AbstractVoucherResponse;

class UnredeemResponse extends AbstractVoucherResponse implements UnredeemResponseInterface
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
        return 'Has not been implemented yet'; // @TODO: I think this method can be deleted.
    }
}
