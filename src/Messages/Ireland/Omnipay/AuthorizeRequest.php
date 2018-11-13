<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Omnipay;

// @TODO: Could we make them common between the 2 APIs? Ie are they identical?
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

    protected function addProductType($productType, $voucherCode)
    {
        if (!isset($this->productTypes[$productType])) {
            $this->productTypes[$productType] = [
                'cartItemsRequired' => 0,
                'voucherCodes' => []
            ];
        }
        $this->productTypes[$productType]['cartItemsRequired']++;
        $this->productTypes[$productType]['voucherCodes'][] = $voucherCode;
    }

    protected function subtractFromProductTypes($cartItem)
    {
        $thirdPartyID =
            isset($cartItem['thirdPartyID']) && $cartItem['thirdPartyID'] ? $cartItem['thirdPartyID'] : null;
        if ($thirdPartyID) {
            if (isset($this->productTypes[$thirdPartyID])) {
                $this->productTypes[$thirdPartyID]['cartItemsRequired'] -= $cartItem['qty'];
            }
        }
    }

    protected function getRemainingProductTypes()
    {
        $remaining = array_filter(
            $this->productTypes,
            function ($productTypeData) {
                return $productTypeData['cartItemsRequired'] > 0;
            }
        );

        return $remaining;
    }

    public function getData()
    {
        return $this->voucherCodes;
    }

    public function sendData($data)
    {
        $this->resetProductTypes();
        $result = [];
        $voucherTotalValue = 0;
        try {
            // This doesn't actually send any data itself; it validates each voucher in the set via the
            // validate() method, then checks that the set of vouchers is compatible with the purchase
            // items.
            foreach ($data as $voucherCode) {
                $validateRequest = clone $this->validateRequest;
                $validateRequest->setVoucherCode($voucherCode);
                /** @var ValidateResponse $response */
                $response = $validateRequest->send();
                if (!$response->success()) {
                    throw new \RuntimeException($response->getErrorMessage());
                }
                $result[] = ['voucherCode' => $voucherCode, 'value' => $response->getValue()];
                $this->addProductType($response->getProductType(), $voucherCode);
                $voucherTotalValue += $response->getValue();
            }
            // Check that there are enough items in the cart for all the voucher's product types.
            // Go through the cart, decrementing the voucher product type count for each item. At the end, if
            // any product types have a positive count, the corresponding voucher is not valid.
            foreach ($this->cartItems as $cartItem) {
                $this->subtractFromProductTypes($cartItem);
            }
            $oversubscribed = $this->getRemainingProductTypes();
            if (count($oversubscribed)) {
                return new AuthorizeResponse(
                    $this,
                    'One or more vouchers cannot be assigned to items in your cart',
                    $oversubscribed
                );
            }
            // Check that the total voucher value is not greater than the cart total.
            if ($voucherTotalValue > $this->cartTotal) {
                return new AuthorizeResponse($this, 'Voucher total is greater than the cart total');
            }

            // @TODO: We need to include an array of the voucher codes, and possibly our generated unique id from
            // @TODO: the requests. Might need to include these in all instantiations of AuthorizeResponse, and
            // @TODO: include a flag to say whether or not there was an error.
            return new AuthorizeResponse($this, $result);

        } catch (\Exception $e) {
            return new AuthorizeResponse($this, $e->getMessage());
        }
    }
}
