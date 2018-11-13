<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Voucher;

use DigiTickets\TescoClubcard\Messages\AbstractMessage;
use DigiTickets\TescoClubcard\Messages\Ireland\Common\AbstractApiRequest;
use DigiTickets\TescoClubcard\Messages\Ireland\Responses\AbstractResponse;
use DigiTickets\TescoClubcard\Messages\Ireland\Responses\ValidateResponse;
use DigiTickets\TescoClubcard\Messages\ValidateMessage;

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
}
