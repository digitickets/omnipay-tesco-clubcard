<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Requests;

use DigiTickets\TescoClubcard\Messages\Ireland\Responses\PurchaseResponse;
use DigiTickets\TescoClubcard\Messages\Ireland\Responses\RedeemResponse;

class RefundRequest extends AuthorizeRequest
{
    /**
     * @var UnredeemRequest
     */
    private $unredeemRequest;

    /**
     * Store the instance of the unredeem request, which we'll use to unredeem the voucher.
     * @param $unredeemRequest
     */
    public function setUnredeemRequest($unredeemRequest)
    {
        $this->unredeemRequest = $unredeemRequest;
    }

    public function sendData($data)
    {
        /** @var UnredeemResponse $response */
        $unredeemResponse = $unredeemRequest->send();

        return new RefundResponse($this, $unredeemResponse);
    }
}
