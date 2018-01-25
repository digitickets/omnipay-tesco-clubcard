<?php

namespace DigiTickets\TescoClubcard\Requests;

use DigiTickets\TescoClubcard\Responses\Interfaces\RedeemResponseInterface;
use DigiTickets\TescoClubcard\Responses\Interfaces\UnredeemResponseInterface;
use DigiTickets\TescoClubcard\Responses\Interfaces\ValidateResponseInterface;

use Omnipay\Common\AbstractGateway;

abstract class AbstractTescoClubcardGateway
{
    /**
     * @return string
     */
    abstract public function getUrl();

    /**
     * @param string $supplierCode
     */
    protected function setSupplierCode($supplierCode)
    {
        error_log('setSupplierCode: '.$supplierCode);
        return $this->setParameter('supplierCode', $supplierCode);
    }

    /**
     * @return string
     */
    public function getSupplierCode()
    {
        error_log('getSupplierCode: '.$this->getParameter('supplierCode'));

        return $this->getParameter('supplierCode');
    }

    /**
     * @param string $thirdPartyIdentifier
     */
    protected function setThirdPartyIdentifier($thirdPartyIdentifier)
    {
        error_log('setThirdPartyIdentifier: '.$thirdPartyIdentifier);
        return $this->setParameter('thirdPartyIdentifier', $thirdPartyIdentifier);
    }

    /**
     * @return string
     */
    public function getThirdPartyIdentifier()
    {
        error_log('getThirdPartyIdentifier: '.$this->getParameter('thirdPartyIdentifier'));

        return $this->getParameter('thirdPartyIdentifier');
    }

    /**
     * @return string
     */
    public function getAppKeyToken()
    {
        return $this->getParameter('appKeyToken');
    }

    /**
     * @param string $appKeyToken
     */
    public function setAppKeyToken($appKeyToken)
    {
        return $this->setParameter('appKeyToken', $appKeyToken);
    }

    /**
     * @return string
     */
    public function getAppKey()
    {
        return $this->getParameter('appKey');
    }

    /**
     * @param string $appKey
     */
    public function setAppKey($appKey)
    {
        return $this->setParameter('appKey', $appKey);
    }

    protected function generateTransactionId()
    {
        return $this->generateGuid();
    }

    protected function generateRequestId()
    {
        return $this->generateGuid();
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
