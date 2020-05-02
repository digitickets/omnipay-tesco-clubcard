<?php

namespace DigiTickets\TescoClubcard\Messages\Uk\Voucher;

use DigiTickets\TescoClubcard\Messages\AbstractMessage;
use DigiTickets\TescoClubcard\Messages\Uk\Common\AbstractUkApiRequest;
use DigiTickets\TescoClubcard\Messages\Uk\Messages\RedeemMessage;
use DigiTickets\TescoClubcard\Messages\Uk\Voucher\AbstractResponse;
use DigiTickets\TescoClubcard\Messages\Uk\Voucher\RedeemResponse;

class RedeemRequest extends AbstractUkApiRequest
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
