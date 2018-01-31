<?php

namespace DigiTickets\TescoClubcard;

use DigiTickets\TescoClubcard\Messages\Ireland\Requests\AuthorizeRequest;
use DigiTickets\TescoClubcard\Messages\Ireland\Requests\PurchaseRequest;
use DigiTickets\TescoClubcard\Messages\Ireland\Requests\RedeemRequest;
use DigiTickets\TescoClubcard\Messages\Ireland\Requests\UnredeemRequest;
use DigiTickets\TescoClubcard\Messages\Ireland\Requests\ValidateRequest;
use Omnipay\Common\Message\AbstractRequest;

class IrelandGateway extends AbstractTescoClubcardGateway
{
    public function getName()
    {
        return 'Tesco Clubcard Boost';
    }

    public function authorize(array $parameters = array())
    {
        $parameters['validateRequest'] = $this->validate($parameters);
        return $this->createRequest(AuthorizeRequest::class, $parameters);
    }

    public function purchase(array $parameters = array())
    {
        $parameters['validateRequest'] = $this->validate($parameters);
        $parameters['redeemRequest'] = $this->redeem($parameters);
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

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
