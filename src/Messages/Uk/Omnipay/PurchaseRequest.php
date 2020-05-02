<?php

namespace DigiTickets\TescoClubcard\Messages\Uk\Omnipay;

use DigiTickets\TescoClubcard\Messages\Uk\Voucher\RedeemRequest;

class PurchaseRequest extends RedeemRequest
{
    /**
     * @param RequestInterface $request
     * @param mixed $response
     * @return AbstractResponse
     */
    protected function buildResponse($request, $response)
    {
        return new PurchaseResponse($request, $response);
    }

    protected function getListenerAction(): string
    {
        return 'purchaseRequestSend';
    }
}