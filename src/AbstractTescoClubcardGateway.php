<?php

namespace DigiTickets\TescoClubcard\Requests;

use DigiTickets\TescoClubcard\Responses\Interfaces\RedeemResponseInterface;
use DigiTickets\TescoClubcard\Responses\Interfaces\UnredeemResponseInterface;
use DigiTickets\TescoClubcard\Responses\Interfaces\ValidateResponseInterface;

abstract class AbstractTescoClubcardGateway
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

    /**
     * @return string
     */
    abstract public function getUrl();

    /**
     * Initialise the request with parameters.
     *
     * If any unknown parameters passed, they will be ignored.
     *
     * @param array $parameters An associative array of parameters
     *
     * @return AbstractTescoClubcardGateway
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

    /**
     * @param string $supplierCode
     */
    protected function setSupplierCode($supplierCode)
    {
        error_log('setSupplierCode: '.$supplierCode);
        $this->supplierCode = $supplierCode;
    }

    /**
     * @return string
     */
    public function getSupplierCode()
    {
        error_log('getSupplierCode: '.$this->supplierCode);

        return $this->supplierCode;
    }

    /**
     * @param string $thirdPartyIdentifier
     */
    protected function setThirdPartyIdentifier($thirdPartyIdentifier)
    {
        error_log('setThirdPartyIdentifier: '.$thirdPartyIdentifier);
        $this->thirdPartyIdentifier = $thirdPartyIdentifier;
    }

    /**
     * @return string
     */
    public function getThirdPartyIdentifier()
    {
        error_log('getThirdPartyIdentifier: '.$this->thirdPartyIdentifier);

        return $this->thirdPartyIdentifier;
    }

    /**
     * @return string
     */
    public function getAppKeyToken()
    {
        return $this->appKeyToken;
    }

    /**
     * @param string $appKeyToken
     */
    public function setAppKeyToken($appKeyToken)
    {
        $this->appKeyToken = $appKeyToken;
    }

    /**
     * @return string
     */
    public function getAppKey()
    {
        return $this->appKey;
    }

    /**
     * @param string $appKey
     */
    public function setAppKey($appKey)
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

    /**
     * @param string $voucherNumber
     * @return ValidateResponseInterface
     */
    abstract public function validate($voucherNumber);

    /**
     * @param string $voucherNumber
     * @return RedeemResponseInterface
     */
    abstract public function redeem($voucherNumber);

    /**
     * @param string $voucherNumber
     * @return UnredeemResponseInterface
     */
    abstract public function unredeem($voucherNumber);

    // @TODO: Do "cancel" and "add reference" at some point.
}
