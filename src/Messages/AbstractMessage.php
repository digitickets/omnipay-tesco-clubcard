<?php

namespace DigiTickets\TescoClubcard\Messages;

class AbstractMessage
{
    const REQUEST_TYPE = '!';

    /**
     * @var string $voucherNumber
     */
    private $voucherNumber;

    public function __construct(string $voucherNumber)
    {
        $this->voucherNumber = $voucherNumber;
    }

    public function getVoucherNumber(): string
    {
        return $this->voucherNumber;
    }

    public function getRequestType(): string
    {
        assert(static::REQUEST_TYPE != self::REQUEST_TYPE, 'Request type must be specified in the subclass');

        return static::REQUEST_TYPE;
    }
}
