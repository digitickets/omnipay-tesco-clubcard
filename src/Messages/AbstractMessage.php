<?php

namespace DigiTickets\TescoClubcard\Messages;

class AbstractMessage
{
    const REQUEST_TYPE = '!';

    /**
     * @var string $voucherNumber
     */
    private $voucherNumber;

    /**
     * AbstractMessage constructor.
     * @param string $voucherNumber
     */
    public function __construct($voucherNumber)
    {
        $this->voucherNumber = $voucherNumber;
    }

    /**
     * @return string
     */
    public function getVoucherNumber()
    {
        return $this->voucherNumber;
    }

    /**
     * @return string
     */
    public function getRequestType()
    {
        assert(static::REQUEST_TYPE != self::REQUEST_TYPE, 'Request type must be specified in the subclass');

        return static::REQUEST_TYPE;
    }
}
