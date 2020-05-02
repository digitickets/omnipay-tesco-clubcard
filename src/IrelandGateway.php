<?php

namespace DigiTickets\TescoClubcard;

use DigiTickets\TescoClubcard\Messages\Ireland\Omnipay\AuthorizeRequest;
use DigiTickets\TescoClubcard\Messages\Ireland\Omnipay\PurchaseRequest;
use DigiTickets\TescoClubcard\Messages\Ireland\Omnipay\RefundRequest;
use DigiTickets\TescoClubcard\Messages\Ireland\Voucher\RedeemRequest;
use DigiTickets\TescoClubcard\Messages\Ireland\Voucher\UnredeemRequest;
use DigiTickets\TescoClubcard\Messages\Ireland\Voucher\ValidateRequest;
use Omnipay\Common\Message\AbstractRequest;

class IrelandGateway extends AbstractTescoClubcardGateway
{
    public function getName()
    {
        return 'Tesco Clubcard Boost';
    }

    protected function createRequest($class, array $parameters)
    {
        $parameters['gateway'] = $this;

        return parent::createRequest($class, $parameters);
    }

    public function authorize(array $parameters = array())
    {
        return $this->createRequest(AuthorizeRequest::class, $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    public function refund(array $parameters = array())
    {
        $parameters['unredeemRequest'] = $this->unredeem($parameters);
        return $this->createRequest(RefundRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest
     */
    public function validate(array $parameters = array())
    {
        return $this->createRequest(ValidateRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest
     */
    public function redeem(array $parameters = array())
    {
        return $this->createRequest(RedeemRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest
     */
    public function unredeem(array $parameters = array())
    {
        return $this->createRequest(UnredeemRequest::class, $parameters);
    }
}
