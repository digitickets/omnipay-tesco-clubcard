<?php

namespace DigiTickets\TescoClubcard\Messages;

use DigiTickets\OmnipayAbstractVoucher\AbstractMessage;

class ValidateMessage extends AbstractMessage
{
    const REQUEST_TYPE = 'Validation';
}
