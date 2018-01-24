<?php

namespace DigiTickets\Integration\TescoClubcard\Api\Responses\Interfaces;

interface AbstractResponseInterface
{
    public function success(): bool;

    /**
     * @return string|null
     */
    public function getErrorMessage();
}
