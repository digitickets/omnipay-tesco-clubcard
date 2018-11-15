<?php

namespace DigiTickets\TescoClubcard\Messages;

use DigiTickets\OmnipayAbstractVoucher\AbstractMessage;

class RedeemMessage extends AbstractMessage
{
    const REQUEST_TYPE = 'Redemption';
}
