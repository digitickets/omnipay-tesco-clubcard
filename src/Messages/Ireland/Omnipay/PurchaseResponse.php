<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Omnipay;

use DigiTickets\TescoClubcard\Messages\Interfaces\PurchaseResponseInterface;
use Omnipay\Common\Message\RequestInterface;

class PurchaseResponse extends AuthorizeResponse implements PurchaseResponseInterface
{
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        // Just change "Authorised" to "Redeemed" in 2 attributes.
        if ($this->isSuccessful()) {
            $this->responseCode = 'Redeemed';
            $this->message = $this->responseCode;
        }
    }
}
