<?php

namespace DigiTickets\TescoClubcard\Uk\Responses;

abstract class AbstractResponse
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

    public function __construct(\stdClass $response)
    {
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
    public function success()
    {
        return $this->responseIsValid && $this->getTokenAttribute('TokenStatus') == $this->getSuccessStatusCode();
    }
}
