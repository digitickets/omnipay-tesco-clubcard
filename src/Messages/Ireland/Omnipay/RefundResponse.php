<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Omnipay;

use DigiTickets\TescoClubcard\Messages\Ireland\Voucher\UnredeemRequest;
use DigiTickets\TescoClubcard\Messages\Ireland\Voucher\UnredeemResponse;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class RefundResponse extends AbstractResponse
{
    /**
     * @var UnredeemRequest
     */
    protected $request;
    /**
     * @var UnredeemResponse
     */
    private $unredeemResponse;

    public function __construct(RequestInterface $request, UnredeemResponse $response)
    {
        parent::__construct($request, $response);

        $this->unredeemResponse = $response;
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->unredeemResponse->isSuccessful();
    }

    /**
     * Response Message
     *
     * @return null|string A response message from the payment gateway
     */
    public function getMessage()
    {
        return $this->unredeemResponse->getErrorMessage();
    }

    /**
     * Response code
     *
     * @return null|string A response code from the payment gateway
     */
    public function getCode()
    {
        return $this->request->getTransactionReference();
    }

    /**
     * Gateway Reference
     *
     * @return null|string A reference provided by the gateway to represent this transaction
     */
    public function getTransactionReference()
    {
        return $this->request->getTransactionReference();
    }

    /**
     * Get the raw data array for this message. The format of this varies from gateway to
     * gateway, but will usually be either an associative array, or a SimpleXMLElement.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->getCode();
    }
}
