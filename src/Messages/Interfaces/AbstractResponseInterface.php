<?php

namespace DigiTickets\TescoClubcard\Messages\Interfaces;

interface AbstractResponseInterface
{
//    /**
//     * @return bool
//     */
//    public function success();

    /**
     * @return string|null
     */
    public function getErrorMessage();
}
