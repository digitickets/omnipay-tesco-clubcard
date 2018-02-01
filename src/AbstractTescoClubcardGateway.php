<?php

namespace DigiTickets\TescoClubcard;

use DigiTickets\TescoClubcard\Messages\Interfaces\RedeemResponseInterface;
use DigiTickets\TescoClubcard\Messages\Interfaces\UnredeemResponseInterface;
use DigiTickets\TescoClubcard\Messages\Interfaces\ValidateResponseInterface;
use Omnipay\Common\AbstractGateway;

/**
 * Class AbstractTescoClubcardGateway
 *
 * @method RequestInterface completeAuthorize(array $options = array()) (Optional method)
 *         Handle return from off-site gateways after authorization
 * @method RequestInterface capture(array $options = array())           (Optional method)
 *         Capture an amount you have previously authorized
 * @method RequestInterface completePurchase(array $options = array())  (Optional method)
 *         Handle return from off-site gateways after purchase
 * @method RequestInterface void(array $options = array())              (Optional method)
 *         Generally can only be called up to 24 hours after submitting a transaction
 * @method RequestInterface createCard(array $options = array())        (Optional method)
 *         The returned response object includes a cardReference, which can be used for future transactions
 * @method RequestInterface updateCard(array $options = array())        (Optional method)
 *         Update a stored card
 * @method RequestInterface deleteCard(array $options = array())        (Optional method)
 *         Delete a stored card
 */
abstract class AbstractTescoClubcardGateway extends AbstractGateway
{
    // These are standard omnipay methods.
    abstract public function authorize(array $parameters = array());
    abstract public function purchase(array $parameters = array());
    abstract public function refund(array $parameters = array());

    // These are the methods that the above methods actually use to communicate with the API.
    /**
     * @param array $parameters
     * @return ValidateResponseInterface
     */
    abstract public function validate(array $parameters = array());

    /**
     * @param array $parameters
     * @return RedeemResponseInterface
     */
    abstract public function redeem(array $parameters = array());

    /**
     * @param array $parameters
     * @return UnredeemResponseInterface
     */
    abstract public function unredeem(array $parameters = array());

    abstract public function getName();

    // Methods to set/get the various credentials.
    /**
     * @param string $supplierCode
     * @return AbstractTescoClubcardGateway
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
     * @return AbstractTescoClubcardGateway
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
        error_log(
            'AbstractTescoClubcardGateway - getThirdPartyIdentifier: '.$this->getParameter('thirdPartyIdentifier')
        );

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
     * @return AbstractTescoClubcardGateway
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
     * @return AbstractTescoClubcardGateway
     */
    public function setAppKey($appKey)
    {
        return $this->setParameter('appKey', $appKey);
    }

    // @TODO: Do "cancel" and "add reference" at some point.
}
