<?php

namespace DigiTickets\TescoClubcard\Responses\Rewards;

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
        error_log('$response: '.var_export($response, true));
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
        error_log('Response is valid? '.var_export($this->responseIsValid, true));
    }

    private function getTokenAttribute(string $attribute)
    {
        if (property_exists($this->tokenDetails, $attribute)) {
            return $this->tokenDetails->$attribute;
        }

        return null;
    }

    abstract protected function getSuccessStatusCode(): string;

    public function success(): bool
    {
        return $this->responseIsValid && $this->getTokenAttribute('TokenStatus') == $this->getSuccessStatusCode();
    }
}
