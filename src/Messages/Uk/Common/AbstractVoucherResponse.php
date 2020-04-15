<?php

namespace DigiTickets\TescoClubcard\Messages\Uk\Common;

use DigiTickets\OmnipayAbstractVoucher\VoucherResponseInterface;
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

    public function __construct(RequestInterface $request, $response)
    {
        $this->request = $request;
        $this->response = $response;

        $this->init();
    }

    private function init()
    {
        if (property_exists($this->response, 'TransactionResponseCode') &&
            $this->response->TransactionResponseCode == self::RESPONSE_CODE_SUCCESS) {
            if (property_exists($this->response, 'TokenDetailsList') &&
                is_array($this->response->TokenDetailsList) &&
                count($this->response->TokenDetailsList) == 1) {
                $this->responseIsValid = true;
                $this->tokenDetails = reset($this->response->TokenDetailsList);
            }
        }
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
        if ($this->responseIsValid) {
            switch ($this->getTokenAttribute('TokenStatus')) {
                case self::STATUS_REDEEMED:
                    return 'Sorry, that voucher has already been redeemed';
                    break;
                case self::STATUS_CANCELLED:
                    return 'Sorry, that voucher has been cancelled';
                    break;
                case self::STATUS_EXPIRED:
                    return 'Sorry, that voucher has expired';
                    break;
                case self::STATUS_NOT_FOUND:
                    return 'Sorry, that voucher was not found';
                    break;
                default:
                    return sprintf('Something went wrong when validating that voucher (%s)', $this->getTokenAttribute('TokenStatus'));
                    break;
            }
        }

        return 'There was a problem communicating with the Tesco server';
    }

}
