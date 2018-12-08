<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Voucher;

use DigiTickets\TescoClubcard\Messages\Ireland\Common\AbstractVoucherResponse;

class UnredeemResponse extends AbstractVoucherResponse
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
        // @TODO: extract the (error) message out of the response.
        return $this->success() ? 'The voucher was unredeemed' : 'The voucher could not be unredeemed';
    }
}
