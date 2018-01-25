<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Requests;

use DigiTickets\TescoClubcard\Messages\AbstractMessage;
use DigiTickets\TescoClubcard\Messages\Ireland\Responses\ValidateResponse;
use DigiTickets\TescoClubcard\Messages\ValidateMessage;

class ValidateRequest extends AbstractRemoteRequest
{
    /**
     * @param string $data
     * @return AbstractMessage
     */
    protected function buildMessage()
    {
        return new ValidateMessage($this->getVoucherCode());
    }

    /**
     * @param RequestInterface $request
     * @param mixed $response
     * @return AbstractRemoteResponse
     */
    protected function buildResponse($request, $response)
    {
        return new ValidateResponse($request, $response);
    }
}