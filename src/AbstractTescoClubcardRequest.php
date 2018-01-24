<?php

namespace DigiTickets\TescoClubcard\Requests;

use DigiTickets\TescoClubcard\Responses\Interfaces\RedeemResponseInterface;
use DigiTickets\TescoClubcard\Responses\Interfaces\UnredeemResponseInterface;
use DigiTickets\TescoClubcard\Responses\Interfaces\ValidateResponseInterface;

abstract class AbstractTescoClubcardRequest
{
    /**
     * @var string
     */
    private $supplierCode;
    /**
     * @var string
     */
    private $thirdPartyIdentifier;
    /**
     * @var string
     */
    private $appKeyToken;
    /**
     * @var string
     */
    private $appKey;

    abstract public function getUrl(): string;

    /**
     * Initialise the request with parameters.
     *
     * If any unknown parameters passed, they will be ignored.
     *
     * @param array $parameters An associative array of parameters
     *
     * @return AbstractTescoClubcardRequest
     */
    public function initialise($parameters = null)
    {
        error_log('Initialising with: '.var_export($parameters, true));
        if (is_array($parameters)) {
            foreach ($parameters as $key => $value) {
                $method = 'set'.ucfirst(camel_case($key));
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }

        return $this;
    }

    protected function generateGuid()
    {
        // Found this code online. Is there an alternative already in the system? Should this be made global?
        $charId = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45); // "-"
        $uuid =
            substr($charId, 0, 8).$hyphen.
            substr($charId, 8, 4).$hyphen.
            substr($charId, 12, 4).$hyphen.
            substr($charId, 16, 4).$hyphen.
            substr($charId, 20, 12);

        return $uuid;
    }

    protected function setSupplierCode(string $supplierCode)
    {
        error_log('setSupplierCode: '.$supplierCode);
        $this->supplierCode = $supplierCode;
    }

    public function getSupplierCode(): string
    {
        error_log('getSupplierCode: '.$this->supplierCode);

        return $this->supplierCode;
    }

    protected function setThirdPartyIdentifier(string $thirdPartyIdentifier)
    {
        error_log('setThirdPartyIdentifier: '.$thirdPartyIdentifier);
        $this->thirdPartyIdentifier = $thirdPartyIdentifier;
    }

    public function getThirdPartyIdentifier(): string
    {
        error_log('getThirdPartyIdentifier: '.$this->thirdPartyIdentifier);

        return $this->thirdPartyIdentifier;
    }

    public function getAppKeyToken(): string
    {
        return $this->appKeyToken;
    }

    public function setAppKeyToken(string $appKeyToken)
    {
        $this->appKeyToken = $appKeyToken;
    }

    public function getAppKey(): string
    {
        return $this->appKey;
    }

    public function setAppKey(string $appKey)
    {
        $this->appKey = $appKey;
    }

    protected function generateTransactionId()
    {
        return $this->generateGuid();
    }

    protected function generateRequestId()
    {
        return $this->generateGuid();
    }

    abstract public function validate(string $voucherNumber): ValidateResponseInterface;

    abstract public function redeem(string $voucherNumber): RedeemResponseInterface;

    abstract public function unredeem(string $voucherNumber): UnredeemResponseInterface;

    // @TODO: Do "cancel" and "add reference" at some point.
}
