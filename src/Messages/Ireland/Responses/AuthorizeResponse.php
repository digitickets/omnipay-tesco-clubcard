<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Responses;

use DigiTickets\TescoClubcard\Messages\Interfaces\AuthorizeResponseInterface;

// @TODO: We may need to create a parent abstract class, and move some/all of the contents of this class into it.
// @TODO: Or maybe just make this a generic class that both authorize() and purchase() return.
class AuthorizeResponse implements AuthorizeResponseInterface
{
    /**
     * @var string|null
     */
    private $errorMessage;

    public function __construct($errorMsg = null)
    {
        $this->errorMessage = $errorMsg;
    }

    /**
     * @return bool
     */
    public function success()
    {
        return is_null($this->errorMessage);
    }

    /**
     * @return string|null
     */
    public function getErrorMessage()
    {
        return $this->success() ? null : $this->errorMessage;
    }
}
