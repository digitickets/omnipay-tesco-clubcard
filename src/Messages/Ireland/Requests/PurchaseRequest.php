<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Requests;

class PurchaseRequest extends AbstractRemoteRequest
{
    /**
     * @param string $data
     * @return AbstractMessage
     */
    protected function buildMessage($data)
    {
        // @TODO: PurchaseMessage not defined yet.
        return new PurchaseMessage($data);
    }
    /**
     * @param RequestInterface $request
     * @param mixed $response
     * @return AbstractRemoteResponse
     */
    protected function buildResponse($request, $response)
    {
        // @TODO: PurchaseResponse not defined yet. Will need to add a request property + getter.
        return new PurchaseResponse($request, $response);
    }
}