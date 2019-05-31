<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Common;

use DigiTickets\OmnipayAbstractVoucher\VoucherResponseInterface;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

abstract class AbstractVoucherResponse extends AbstractResponse implements VoucherResponseInterface
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

    /**
     * @var string|null
     */
    private $message;

    public function __construct(RequestInterface $request, \SimpleXMLElement $response)
    {
        $this->request = $request;
        $this->responseXml = $response;

        $this->init();
    }

    private function init()
    {
        $this->message = 'Unexpected response was returned';
        // Grab the first (and only) response node within the response object, and store it in the class.
        $responseNodes = $this->responseXml->xpath('//Response');
        if (count($responseNodes)) {
            $this->responseNode = array_shift($responseNodes);
            $responseCode = $this->get('ResponseCode');
            $this->responseIsValid = $responseCode == self::RESPONSE_CODE_SUCCESS;
            if ($this->responseIsValid) {
                // Check the status.
                if ($this->get('Status') == $this->getSuccessStatusCode()) {
                    // Check the expiry date - the response comes back with everything valid, even if the voucher has expired.
                    $expiryDate = $this->get('ExpiryDate');
                    if ($expiryDate) {
                        /** @var \DateTime $expiryDateObject */
                        $expiryDateObject = \DateTime::createFromFormat('d/m/Y H:i:s', $expiryDate);
                        if ($expiryDateObject->getTimestamp() < time()) {
                            $this->responseIsValid = false;
                            $this->message = 'Voucher expired at '.$expiryDateObject->format('Y-m-d H:i:s');
                        }
                    }
                } else {
                    $this->message = $this->buildErrorMessage($this->get('Status'));
                }
            } else {
                $this->message = $responseCode; // For now, the error message is the response code, eg, "StatusChangeFail".
            }

        }
    }

    /**
     * @param string $attribute
     * @return string
     */
    protected function get($attribute)
    {
        if ($this->responseNode && property_exists($this->responseNode, $attribute)) {
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

    public function isSuccessful(): bool
    {
        // @TODO: Should it check things like expiry date?
        return $this->responseIsValid && $this->get('Status') == $this->getSuccessStatusCode();
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

    private function buildErrorMessage($statusCode)
    {
        switch ($statusCode) {
            case self::STATUS_REDEEMED:
                return 'Voucher has already been redeemed - cannot use';
                break;
            case self::STATUS_CANCELLED:
            case self::STATUS_INVOICED:
                return 'Voucher has been '.strtolower($statusCode);
                break;
            case self::STATUS_EXPIRED:
                return 'Voucher has expired';
                break;
            case self::STATUS_NOT_FOUND:
                return 'Voucher was not found';
                break;
            case self::STATUS_ACTIVE:
                return 'Voucher is currently active';
                break;
            default:
                return $statusCode;
                break;
        }
    }
}
