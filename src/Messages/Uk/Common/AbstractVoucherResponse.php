<?php

namespace DigiTickets\TescoClubcard\Messages\Uk\Common;

use DigiTickets\OmnipayAbstractVoucher\VoucherResponseInterface;
use DigiTickets\ValueObjects\DateTime;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * @TODO: This seems remarkably similar to the Ireland version.
 */
abstract class AbstractVoucherResponse extends AbstractResponse implements VoucherResponseInterface
{
    const RESPONSE_CODE_SUCCESS = '0';

    const STATUS_REDEEMED = 'Redeemed';
    const STATUS_CANCELLED = 'Cancelled';
    const STATUS_ACTIVE = 'Active';
    const STATUS_EXPIRED = 'Expired';
    const STATUS_NOT_FOUND = 'NotFound';

    /**
     * @var \stdClass
     */
    private $response;

    /**
     * @var bool
     */
    private $responseIsValid = false;

    /**
     * @var \stdClass|null
     */
    private $tokenDetails;

    /**
     * @var string|null
     */
    private $tokenCode;

    /**
     * @var string
     */
    private $message;

    public function __construct(RequestInterface $request, $response)
    {
        $this->request = $request;
        $this->response = $response;

        $this->init();
    }

    private function init()
    {
        // Assume something went wrong until we find out otherwise.
        $this->responseIsValid = false;
        $this->message = 'An error occured when communicating with Tesco';
        $this->tokenCode = null;
        if (property_exists($this->response, 'TransactionResponseCode') &&
            $this->response->TransactionResponseCode == self::RESPONSE_CODE_SUCCESS) {
            if (property_exists($this->response, 'TokenDetailsList') &&
                is_array($this->response->TokenDetailsList) &&
                count($this->response->TokenDetailsList) == 1) {
                $this->tokenDetails = reset($this->response->TokenDetailsList);
                $this->message = $this->getTokenAttribute('TokenStatus');
                // Check the expiry date.
                $expiryDate = $this->getTokenAttribute('TokenExpiryDate');
                if ($expiryDate) {
                    $expiryDateTime = DateTime::parse($expiryDate, false, 'd/m/Y H:i:s');
                    if ($expiryDateTime->getTimestamp() < time()) {
                        $this->message = 'Voucher expired at '.$expiryDateTime->format('Y-m-d H:i:s');
                    } else {
                        // We're okay - it expires in the future.
                        $this->responseIsValid = true;
                    }
                } else {
                    $this->message = 'Expiry date is missing from voucher';
                }
            } else {
                $this->message = 'Invalid response from Tesco server';
            }
        } else {
            $this->message = 'Request was invalid';
        }
        $this->tokenCode = $this->getTokenAttribute('TokenCode');
    }

    /**
     * @param string $attribute
     * @return null
     */
    private function getTokenAttribute($attribute)
    {
        if (property_exists($this->tokenDetails, $attribute)) {
            return $this->tokenDetails->$attribute;
        }

        return null;
    }

    /**
     * @return string
     */
    abstract protected function getSuccessStatusCode();

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->responseIsValid && $this->getTokenAttribute('TokenStatus') == $this->getSuccessStatusCode();
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getTransactionReference()
    {
        return $this->tokenCode;
    }
}
