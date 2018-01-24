<?php

namespace DigiTickets\Integration\TescoClubcard\Api\Responses\Interfaces;

interface ValidateResponseInterface extends AbstractResponseInterface
{
    public function getValue(): float;
}
