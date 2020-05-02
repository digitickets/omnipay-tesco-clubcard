<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Voucher;

use DigiTickets\TescoClubcard\Messages\AbstractMessage;
use DigiTickets\TescoClubcard\Messages\Ireland\Common\AbstractIrelandApiRequest;
use DigiTickets\TescoClubcard\Messages\Ireland\Messages\UnredeemMessage;
use DigiTickets\TescoClubcard\Messages\Ireland\Voucher\AbstractResponse;
use DigiTickets\TescoClubcard\Messages\Ireland\Voucher\UnredeemResponse;

class UnredeemRequest extends AbstractIrelandApiRequest
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
