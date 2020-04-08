<?php

namespace DigiTickets\TescoClubcard\Messages\Uk\Voucher;

use DigiTickets\TescoClubcard\Messages\AbstractMessage;
use DigiTickets\TescoClubcard\Messages\Uk\Common\AbstractApiRequest;
use DigiTickets\TescoClubcard\Messages\Uk\Messages\ValidateMessage;

class ValidateRequest extends AbstractApiRequest
{
    /**
     * @return AbstractMessage
     */
    protected function buildMessage()
    {
        return new ValidateMessage($this->getVoucherCode());
    }

    /**
     * @param RequestInterface $request
     * @param mixed $response
     * @return AbstractResponse
     */
    protected function buildResponse($request, $response)
    {
        return new ValidateResponse($request, $response);
    }

    protected function getListenerAction(): string
    {
        return 'validateRequestSend';
    }
}
