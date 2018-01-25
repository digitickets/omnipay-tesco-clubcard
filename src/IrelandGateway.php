<?php

namespace DigiTickets\TescoClubcard;

use DigiTickets\TescoClubcard\Messages\AbstractMessage;
use DigiTickets\TescoClubcard\Messages\RedeemMessage;
use DigiTickets\TescoClubcard\Messages\UnredeemMessage;
use DigiTickets\TescoClubcard\Messages\ValidateMessage;
use DigiTickets\TescoClubcard\Responses\Ireland\RedeemResponse;
use DigiTickets\TescoClubcard\Responses\Ireland\UnredeemResponse;
use DigiTickets\TescoClubcard\Responses\Ireland\ValidateResponse;
use DigiTickets\TescoClubcard\Responses\Interfaces\RedeemResponseInterface;
use DigiTickets\TescoClubcard\Responses\Interfaces\UnredeemResponseInterface;
use DigiTickets\TescoClubcard\Responses\Interfaces\ValidateResponseInterface;

class IrelandGateway extends AbstractTescoClubcardGateway
{
    public function getName()
    {
        return 'Tesco Clubcard Boost';
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest(
            '\DigiTickets\TescoClubcard\Messages\Ireland\Requests\PurchaseRequest',
            $parameters
        );
    }

    public function validate(array $parameters = array())
    {
        return $this->createRequest(
            '\DigiTickets\TescoClubcard\Messages\Ireland\Requests\ValidateRequest',
            $parameters
        );
    }

    /**
     * @param string $voucherNumber
     * @return RedeemResponseInterface
     */
    public function redeem($voucherNumber)
    {
        error_log('/');
        error_log('validate voucher (Ireland): '.$voucherNumber);
        $message = new RedeemMessage($voucherNumber);

        //error_log('Redeem $message: '.var_export($message, true));
        return new RedeemResponse($this->send($message));
    }

    /**
     * @param string $voucherNumber
     * @return UnredeemResponseInterface
     */
    public function unredeem($voucherNumber)
    {
        error_log('/');
        error_log('validate voucher (Ireland): '.$voucherNumber);
        $message = new UnredeemMessage($voucherNumber);

        //error_log('Unredeem $message: '.var_export($message, true));
        return new UnredeemResponse($this->send($message));
    }
}
