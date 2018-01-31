<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Requests;

use DigiTickets\TescoClubcard\Messages\Ireland\Responses\PurchaseResponse;
use DigiTickets\TescoClubcard\Messages\Ireland\Responses\RedeemResponse;

/**
 * Purchase request does everything that AuthorizeRequest does, plus if it's successful, it actually
 * redeems the vouchers.
 */
class PurchaseRequest extends AuthorizeRequest
{
    /**
     * @var RedeemRequest
     */
    private $redeemRequest;

    /**
     * Store the instance of the redeem request, which we'll use to redeem each voucher.
     * @param $redeemRequest
     */
    public function setRedeemRequest($redeemRequest)
    {
        $this->redeemRequest = $redeemRequest;
    }

    public function sendData($data)
    {
        // Do all the authorisation stuff.
        $authorizeResponse = parent::sendData($data);

        // If authorisation was successful, actually redeem the vouchers.
        if (!$authorizeResponse->isSuccessful()) {
            return new PurchaseResponse($this, 'Authorization failed');
        }

error_log('Redeeming the voucher codes here...');
        foreach ($data as $voucherCode) {
error_log('Next voucher to redeem: '.$voucherCode);
            $redeemRequest = clone $this->redeemRequest;
            $redeemRequest->setVoucherCode($voucherCode);
            /** @var RedeemResponse $response */
            $response = $redeemRequest->send();
error_log('It seemed to be successful');
            if (!$response->success()) {
                return new PurchaseResponse($this, 'Failed to redeem all the vouchers');
            }
        }

        $purchaseResponse = new PurchaseResponse($this, $authorizeResponse->getData());

        return $purchaseResponse;
    }
}