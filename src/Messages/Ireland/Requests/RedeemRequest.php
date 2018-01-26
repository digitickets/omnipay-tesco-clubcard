<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Requests;

use DigiTickets\TescoClubcard\Messages\AbstractMessage;
use DigiTickets\TescoClubcard\Messages\Ireland\Responses\AbstractResponse;
use DigiTickets\TescoClubcard\Messages\Ireland\Responses\RedeemResponse;
use DigiTickets\TescoClubcard\Messages\RedeemMessage;

class RedeemRequest extends AbstractRemoteRequest
{
    /**
     * @return AbstractMessage
     */
    protected function buildMessage()
    {
        return new RedeemMessage($this->getVoucherCode());
    }

    /**
     * @param RequestInterface $request
     * @param mixed $response
     * @return AbstractResponse
     */
    protected function buildResponse($request, $response)
    {
        return new RedeemResponse($request, $response);
    }
}
