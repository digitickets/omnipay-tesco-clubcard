<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Voucher;

use DigiTickets\TescoClubcard\Messages\AbstractMessage;
use DigiTickets\TescoClubcard\Messages\Ireland\Common\AbstractIrelandApiRequest;
use DigiTickets\TescoClubcard\Messages\Ireland\Messages\ValidateMessage;
use DigiTickets\TescoClubcard\Messages\Ireland\Voucher\AbstractResponse;
use DigiTickets\TescoClubcard\Messages\Ireland\Voucher\ValidateResponse;

class ValidateRequest extends AbstractIrelandApiRequest
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
