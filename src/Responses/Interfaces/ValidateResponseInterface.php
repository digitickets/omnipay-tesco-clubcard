<?php

namespace DigiTickets\TescoClubcard\Responses\Interfaces;

interface ValidateResponseInterface extends AbstractResponseInterface
{
    /**
     * @return float
     */
    public function getValue();
}
