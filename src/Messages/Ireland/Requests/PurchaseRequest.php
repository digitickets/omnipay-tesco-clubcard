<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Requests;

/**
 * Purchase request does everything that AuthorizeRequest does, plus if it's successful, it actually redeems the vouchers.
 */
class PurchaseRequest extends AuthorizeRequest
{
    public function sendData($data)
    {
        // Do all the authorisation stuff.
        $purchaseResponse = parent::sendData($data);

        // If authorisation was successful, actually redeem the vouchers.
        if ($purchaseResponse->success()) {
            error_log('We would redeem the voucher codes here...');
            error_log('These should be the voucher codes: ' . var_export($data, true));
        }

        return $purchaseResponse;
    }
}
