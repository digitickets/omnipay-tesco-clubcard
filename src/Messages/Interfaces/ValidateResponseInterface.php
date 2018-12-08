<?php

namespace DigiTickets\TescoClubcard\Messages\Interfaces;

// @TODO: I don't think we need this any more.
interface ValidateResponseInterface extends AbstractResponseInterface
{
    /**
     * @return float
     */
    public function getValue();
}
