<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Voucher;

class ValidateResponse extends AbstractVoucherResponse
{
    /**
     * @return string
     */
    protected function getSuccessStatusCode()
    {
        return self::STATUS_ACTIVE;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage()
    {
        // Annoyingly, if the voucher code is not valid, the API response is empty, so we have to
        // have a special check for it.
        if (is_null($this->get('Status'))) {
            return 'Invalid voucher code';
        }

        // Map the status to an error message.
        $map = [
            'NotFound' => 'Voucher was not found',
            'Redeemed' => 'Voucher is already redeemed',
        ];
        $error = isset($map[$this->get('Status')])
            ?
            $map[$this->get('Status')]
            :
            'Unknown error [Ireland ValidateResponse]';

        return $error;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return ((int) $this->get('ValueString')) / 100;
    }

    public function getProductType()
    {
        return $this->get('ProductType');
    }
}
