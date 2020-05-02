<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Voucher;

use DigiTickets\TescoClubcard\Messages\AbstractMessage;
use DigiTickets\TescoClubcard\Messages\Ireland\Common\AbstractIrelandApiRequest;
use DigiTickets\TescoClubcard\Messages\Ireland\Messages\RedeemMessage;
use DigiTickets\TescoClubcard\Messages\Ireland\Voucher\AbstractResponse;
use DigiTickets\TescoClubcard\Messages\Ireland\Voucher\RedeemResponse;

class RedeemRequest extends AbstractIrelandApiRequest
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

    protected function getListenerAction(): string
    {
        return 'redeemRequestSend';
    }
}
