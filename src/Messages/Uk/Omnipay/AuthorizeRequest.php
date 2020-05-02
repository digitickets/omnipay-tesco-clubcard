<?php

namespace DigiTickets\TescoClubcard\Messages\Uk\Omnipay;

use DigiTickets\TescoClubcard\Messages\Uk\Voucher\ValidateRequest;

class AuthorizeRequest extends ValidateRequest
{
    /**
     * @param RequestInterface $request
     * @param mixed $response
     * @return AbstractResponse
     */
    protected function buildResponse($request, $response)
    {
        return new AuthorizeResponse($request, $response);
    }

    protected function getListenerAction(): string
    {
        return 'authorizeRequestSend';
    }
}
