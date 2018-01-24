<?php

namespace DigiTickets\TescoClubcard\Responses\Interfaces;

interface AbstractResponseInterface
{
    public function success(): bool;

    /**
     * @return string|null
     */
    public function getErrorMessage();
}
