<?php

namespace DigiTickets\TescoClubcard;

use DigiTickets\TescoClubcard\Responses\Interfaces\RedeemResponseInterface;
use DigiTickets\TescoClubcard\Responses\Interfaces\UnredeemResponseInterface;
use DigiTickets\TescoClubcard\Responses\Interfaces\ValidateResponseInterface;
use Omnipay\Common\AbstractGateway;

abstract class AbstractTescoClubcardGateway extends AbstractGateway
{
    abstract public function purchase(array $parameters = array());

    /**
     * @param string $supplierCode
     */
    public function setSupplierCode($supplierCode)
    {
        error_log('AbstractTescoClubcardGateway - setSupplierCode: '.$supplierCode);
        return $this->setParameter('supplierCode', $supplierCode);
    }

    /**
     * @return string
     */
    public function getSupplierCode()
    {
        error_log('AbstractTescoClubcardGateway - getSupplierCode: '.$this->getParameter('supplierCode'));

        return $this->getParameter('supplierCode');
    }

    /**
     * @param string $thirdPartyIdentifier
     */
    public function setThirdPartyIdentifier($thirdPartyIdentifier)
    {
        error_log('AbstractTescoClubcardGateway - setThirdPartyIdentifier: '.$thirdPartyIdentifier);
        return $this->setParameter('thirdPartyIdentifier', $thirdPartyIdentifier);
    }

    /**
     * @return string
     */
    public function getThirdPartyIdentifier()
    {
        error_log('AbstractTescoClubcardGateway - getThirdPartyIdentifier: '.$this->getParameter('thirdPartyIdentifier'));

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

    /**
     * @param array $parameters
     * @return ValidateResponseInterface
     */
    abstract public function validate(array $parameters = array());

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
