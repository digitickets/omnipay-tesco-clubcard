<?php

namespace DigiTickets\TescoClubcard\Responses\Interfaces;

interface AbstractResponseInterface
{
    /**
     * @return bool
     */
    public function success();

    /**
     * @return string|null
     */
    public function getErrorMessage();
}
