<?php

namespace DigiTickets\TescoClubcard\Messages;

use DigiTickets\OmnipayAbstractVoucher\AbstractMessage;

class ValidateMessage extends AbstractMessage
{
//    const REQUEST_TYPE = 'Validation'; // @TODO: I think this is used by Ireland
    const REQUEST_TYPE = 'Validate';
}
