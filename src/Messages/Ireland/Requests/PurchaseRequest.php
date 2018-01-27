<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Requests;

class PurchaseRequest extends AbstractRemoteRequest
{
    protected function buildMessage()
    {
        // @TODO: PurchaseMessage not defined yet.
        return new PurchaseMessage();
    }

    protected function buildResponse($request, $response)
    {
        // @TODO: PurchaseResponse not defined yet. Will need to add a request property + getter.
        return new PurchaseResponse($request, $response);
    }
}
