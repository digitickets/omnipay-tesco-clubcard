<?php

namespace DigiTickets\TescoClubcard\Messages\Uk\Omnipay;

use DigiTickets\TescoClubcard\Messages\Uk\Voucher\UnredeemRequest;

class RefundRequest extends UnredeemRequest
{
    public function getVoucherCode()
    {
        return $this->getTransactionReference();
    }

    /**
     * @param RequestInterface $request
     * @param mixed $response
     * @return AbstractResponse
     */
    protected function buildResponse($request, $response)
    {
        return new RefundResponse($request, $response);
    }

    protected function getListenerAction(): string
    {
        return 'RefundRequestSend';
    }
}
