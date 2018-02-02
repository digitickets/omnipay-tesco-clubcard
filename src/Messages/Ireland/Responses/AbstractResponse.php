<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Responses;

use Omnipay\Common\Message\RequestInterface;

abstract class AbstractResponse
{
    const RESPONSE_CODE_FAILURE = 'Failure';
    const RESPONSE_CODE_SUCCESS = 'Success';
    const RESPONSE_CODE_TIMEOUT = 'Timeout';
    const RESPONSE_CODE_RESERVATION_FAIL = 'ReservationFail';
    const RESPONSE_CODE_REDEMPTION_FAIL = 'RedemptionFail';
    const RESPONSE_CODE_UNRESERVATION_FAIL = 'UnreservationFail';
    const RESPONSE_CODE_CANCELLATION_FAIL = 'CancellationFail';
    const RESPONSE_CODE_STATUS_CHANGE_FAIL = 'StatusChangeFail';
    const RESPONSE_CODE_ADD_REFERENCE_FAIL = 'AddReferenceFail';

    const STATUS_NOT_FOUND = 'NotFound';
    const STATUS_ACTIVE = 'Active';
    const STATUS_RESERVED = 'Reserved';
    const STATUS_REDEEMED = 'Redeemed';
    const STATUS_CANCELLED = 'Cancelled';
    const STATUS_EXPIRED = 'Expired';
    const STATUS_INVOICED = 'Invoiced';

    /**
     * @var RequestInterface
     */
    protected $request;
    /**
     * @var bool
     */
    private $responseIsValid = false;
    /**
     * @var \SimpleXMLElement
     */
    private $responseXml;
    /**
     * @var \SimpleXMLElement|null
     */
    private $responseNode;

    public function __construct(RequestInterface $request, \SimpleXMLElement $response)
    {
        $this->request = $request;
        $this->responseXml = $response;

        $this->init();
    }

    private function init()
    {
        // Grab the first (and only) response node within the response object, and store it in the class.
        $responseNodes = $this->responseXml->xpath('//Response');
        if (count($responseNodes)) {
            $this->responseNode = array_shift($responseNodes);
            $this->responseIsValid = $this->get('ResponseCode') == self::RESPONSE_CODE_SUCCESS;
        }
    }

    /**
     * @param string $attribute
     * @return string
     */
    protected function get($attribute)
    {
        if (property_exists($this->responseNode, $attribute)) {
            return (string) $this->responseNode->$attribute;
        }

        return null;
    }

    /**
     * Returns the string that means "success" for the specific request.
     *
     * @return string
     */
    abstract protected function getSuccessStatusCode();

    /**
     * @return bool
     */
    public function success()
    {
        // @TODO: Should it check things like expiry date?
        return $this->responseIsValid && $this->get('Status') == $this->getSuccessStatusCode();
    }
}
