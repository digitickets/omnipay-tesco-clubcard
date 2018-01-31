<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Responses;

use DigiTickets\TescoClubcard\Messages\Interfaces\AuthorizeResponseInterface;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

// @TODO: PurchaseResponse needs to extend this, and set the statuses to "Redeemed" instead of "authorised".
class AuthorizeResponse extends AbstractResponse implements AuthorizeResponseInterface
{
    /**
     * @var bool
     */
    private $successful = false;
    /**
     * @var string|null
     */
    private $message = null;
    /**
     * @var string|null
     */
    private $responseCode = null;

    /**
     * Constructor
     *
     * @param RequestInterface $request the initiating request.
     * @param mixed $data
     */
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);

        // If $data is a string, it means there's been an error; if it's an array, it's an array of voucher codes.
        $this->successful = is_array($this->data);
        if ($this->successful) {
            $this->data = $data; // An array of voucher codes and their values.
            $this->responseCode = 'Authorised'; // @TODO: In the purchase Response, override this with "Redeemed".
            $this->message = $this->responseCode;
        } else {
            $this->data = [];
            $this->message = $data;
        }
    }

    /**
     * Is the response successful?
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return $this->successful;
    }

    /**
     * Response Message
     *
     * @return null|string A response message from the payment gateway
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Response code
     *
     * @return null|string A response code from the payment gateway
     */
    public function getCode()
    {
        return $this->responseCode;
    }

    /**
     * Gateway Reference
     *
     * @return null|string A reference provided by the gateway to represent this transaction
     */
    public function getTransactionReference()
    {
        return json_encode($this->data);
    }
}
