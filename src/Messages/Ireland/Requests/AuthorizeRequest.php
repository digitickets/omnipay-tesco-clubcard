<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Requests;

// @TODO: Could we make them common between the 2 APIs? Ie same methods?
use DigiTickets\TescoClubcard\Messages\Ireland\Responses\AuthorizeResponse;
use DigiTickets\TescoClubcard\Messages\Ireland\Responses\ValidateResponse;
use Omnipay\Common\Message\AbstractRequest;

class AuthorizeRequest extends AbstractRequest
{
    /**
     * @var ValidateRequest
     */
    private $validateRequest;
    /**
     * @var array
     */
    private $cartItems;
    /**
     * @var float
     */
    private $cartTotal;
    /**
     * @var string[]
     */
    private $voucherCodes;
    /**
     * @var array
     */
    private $productTypes;

    /**
     * Store the instance of the validate request, which we'll use to validate each voucher.
     * @param $validateRequest
     */
    public function setValidateRequest($validateRequest)
    {
        $this->validateRequest = $validateRequest;
    }

    public function setCartItems($cartItems)
    {
        $this->cartItems = $cartItems;
    }

    public function setCartTotal($cartTotal)
    {
        $this->cartTotal = $cartTotal;
    }

    public function setVoucherCodes($voucherCodes)
    {
        $this->voucherCodes = $voucherCodes;
    }

    protected function resetProductTypes()
    {
        $this->productTypes = [];
    }

    protected function addProductType($productType)
    {
        if (!isset($this->productTypes[$productType])) {
            $this->productTypes[$productType] = 0;
        }
        $this->productTypes[$productType]++;
    }

    protected function subtractFromProductTypes($cartItem)
    {
        $thirdPartyID = isset($cartItem['thirdPartyID']) && $cartItem['thirdPartyID'] ? $cartItem['thirdPartyID'] : null;
        if ($thirdPartyID) {
            if (isset($this->productTypes[$thirdPartyID])) {
                $this->productTypes[$thirdPartyID] -= $cartItem['qty'];
            }
        }
    }

    protected function anyRemainingProductTypes()
    {
        $remaining = array_filter(
            $this->productTypes,
            function ($productTypeQty) {
                return $productTypeQty > 0;
            }
        );

        return !empty($remaining);
    }

    public function getData()
    {
        return $this->voucherCodes;
    }

    public function sendData($data)
    {
        $this->resetProductTypes();
        try {
            // This doesn't actually send any data; it validates each voucher in the set, then checks
            // that the set of vouchers is compatible with the purchase items.
            foreach ($data as $voucherCode) {
                $validateRequest = clone $this->validateRequest;
                $validateRequest->setVoucherCode($voucherCode);
                /** @var ValidateResponse $response */
                $response = $validateRequest->send();
                if (!$response->success()) {
                    throw new \RuntimeException($response->getErrorMessage());
                }
                $this->addProductType($response->getProductType());
            }
            // Check that there are enough items in the cart for all the voucher's product types.
            // Go through the cart, decrementing the voucher product type count for each item. At the end, if
            // any product types have a positive count, the corresponding voucher is not valid.
            foreach ($this->cartItems as $cartItem) {
                $this->subtractFromProductTypes($cartItem);
            }
            if ($this->anyRemainingProductTypes()) {
                return new AuthorizeResponse('One or more vouchers cannot be assigned to items in your cart');
            }

            return new AuthorizeResponse();

        } catch (\Exception $e) {
            return new AuthorizeResponse($e->getMessage());
        }

    }
}
