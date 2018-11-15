<?php

namespace DigiTickets\TescoClubcard\Messages;

use DigiTickets\OmnipayAbstractVoucher\AbstractMessage;

class UnredeemMessage extends AbstractMessage
{
    const REQUEST_TYPE = 'Unredeem';
}
