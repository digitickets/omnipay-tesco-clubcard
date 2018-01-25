<?php

namespace DigiTickets\TescoClubcard\Requests;

use DigiTickets\TescoClubcard\Messages\AbstractMessage;
use DigiTickets\TescoClubcard\Messages\ValidateMessage;
use DigiTickets\TescoClubcard\Responses\Interfaces\RedeemResponseInterface;
use DigiTickets\TescoClubcard\Responses\Interfaces\UnredeemResponseInterface;
use DigiTickets\TescoClubcard\Responses\Interfaces\ValidateResponseInterface;
use DigiTickets\TescoClubcard\Responses\Uk\RedeemResponse;
use DigiTickets\TescoClubcard\Responses\Uk\UnredeemResponse;
use DigiTickets\TescoClubcard\Responses\Uk\ValidateResponse;
use GuzzleHttp\Client;

class TescoClubcardUKGateway extends AbstractTescoClubcardGateway
{
    /**
     * @return string
     */
    public function getUrl()
    {
        return 'https://clubcard.api.tesco.com/v1.0/TokenProcessorService/';
    }

    public function getAuthKey()
    {
        return 'appKeyToken='.$this->getAppKeyToken().'&appKey='.$this->getAppKey();
    }

    /**
     * @param AbstractMessage $message
     * @return \stdClass
     */
    private function send(AbstractMessage $message)
    {
        error_log('Sending a Uk request');
        // Need to substitute all the things in.
        $message = [
            'TransactionID' => $this->generateTransactionId(),
            'TransactionDateTime' => $this->getTransactionDateTime(),
            'RequestType' => $message->getRequestType(),
            'SupplierCode' => $this->getSupplierCode(),
            'TokenDetailsList' => [
                [
                    'ReferenceNo' => $this->getReferenceNo(),
                    'RequestId' => $this->generateRequestId(),
                    'TokenCode' => $message->getVoucherNumber(),
                ],
            ],
        ];
        error_log('$message: '.var_export($message, true));

        $clientParams = [
            'base_uri' => $this->getUrl(),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => $this->getAuthKey(),
            ],
            'http_errors' => false,
        ];
        error_log('$clientParams: '.var_export($clientParams, true));
        $client = new Client($clientParams);
        $params = [
            'body' => json_encode($message),
        ];
        error_log('$params: '.var_export($params, true));
        $response = $client->post('ManageToken', $params);
        error_log('Uk $response: '.var_export($response, true));

        $result = json_decode($response->getBody()->getContents());
        error_log('Response body: '.var_export($result, true));

        return $result;
    }

    protected function getTransactionDateTime()
    {
        // Eg 24/11/2017 12:23:34
        return (new \DateTime())->format('d/m/Y H:m:i');
    }

    protected function getReferenceNo()
    {
        return 'NOIDEA'; // No idea what this is supposed to be.
    }

    /**
     * @param string $voucherNumber
     * @return ValidateResponseInterface
     */
    public function validate($voucherNumber)
    {
        error_log('/');
        error_log('validate voucher (Uk): '.$voucherNumber);
        $message = new ValidateMessage($voucherNumber);
        //error_log('Validate $message: '.var_export($message, true));
        return new ValidateResponse($this->send($message));
    }

    /**
     * @param string $voucherNumber
     * @return RedeemResponseInterface
     */
    public function redeem($voucherNumber)
    {
        return new RedeemResponse(json_decode('{"dummy":"true"}'));
    }

    /**
     * @param string $voucherNumber
     * @return UnredeemResponseInterface
     */
    public function unredeem($voucherNumber)
    {
        return new UnredeemResponse(json_decode('{"dummy":"true"}'));
    }
}
