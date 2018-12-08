<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Voucher;

use DigiTickets\TescoClubcard\Messages\AbstractMessage;
use DigiTickets\TescoClubcard\Messages\Ireland\Common\AbstractApiRequest;
use DigiTickets\TescoClubcard\Messages\Ireland\Voucher\AbstractResponse;
use DigiTickets\TescoClubcard\Messages\Ireland\Voucher\UnredeemResponse;
use DigiTickets\TescoClubcard\Messages\UnredeemMessage;

class UnredeemRequest extends AbstractApiRequest
{
    /**
     * @return AbstractMessage
     */
    protected function buildMessage()
    {
        return new UnredeemMessage($this->getVoucherCode());
    }

    /**
     * @param RequestInterface $request
     * @param mixed $response
     * @return AbstractResponse
     */
    protected function buildResponse($request, $response)
    {
        return new UnredeemResponse($request, $response);
    }

    protected function getListenerAction(): string
    {
        return 'UnredeemRequestSend';
    }
}
