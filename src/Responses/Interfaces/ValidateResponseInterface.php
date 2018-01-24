<?php

namespace DigiTickets\TescoClubcard\Responses\Interfaces;

interface ValidateResponseInterface extends AbstractResponseInterface
{
    public function getValue(): float;
}
