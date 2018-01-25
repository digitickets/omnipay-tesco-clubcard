<?php

namespace DigiTickets\TescoClubcard\Messages\Interfaces;

interface ValidateResponseInterface extends AbstractResponseInterface
{
    /**
     * @return float
     */
    public function getValue();
}
