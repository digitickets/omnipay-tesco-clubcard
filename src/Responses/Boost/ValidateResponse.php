<?php

namespace DigiTickets\Integration\TescoClubcard\Api\Responses\Boost;

use DigiTickets\Integration\TescoClubcard\Api\Responses\Interfaces\ValidateResponseInterface;

class ValidateResponse extends AbstractResponse implements ValidateResponseInterface
{
    protected function getSuccessStatusCode(): string
    {
        return self::STATUS_ACTIVE;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage()
    {
        // Map the status to an error message.
        $map = [
            'NotFound' => 'Voucher was not found',
        ];
        $error = $map[$this->get('Status')] ?? 'Unknown error [Boost ValidateResponse]';

        return $error;
    }

    public function getValue(): float
    {
        return ((int) $this->get('ValueString')) / 100;
    }
}
