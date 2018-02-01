<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Responses;

use DigiTickets\TescoClubcard\Messages\Ireland\Requests\RedeemRequest;
use DigiTickets\TescoClubcard\Messages\Ireland\Requests\RefundRequest;
use Omnipay\Common\Message\RequestInterface;

class RefundResponse extends AbstractResponse implements RefundResponseInterface
{
    /**
     * @var RedeemRequest
     */
    protected $request;
    /**
     * @var UnredeemResponse
     */
    private $unredeemResponse;

    public function __construct(RequestInterface $request, \SimpleXMLElement $response)
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
        return $this->unredeemResponse->success();
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
        return $this->request->getVoucherCode();
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
}