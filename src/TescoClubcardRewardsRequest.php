<?php

namespace DigiTickets\Integration\TescoClubcard\Api\Requests;

use DigiTickets\Integration\TescoClubcard\Api\Messages\AbstractMessage;
use DigiTickets\Integration\TescoClubcard\Api\Messages\ValidateMessage;
use DigiTickets\Integration\TescoClubcard\Api\Responses\Interfaces\RedeemResponseInterface;
use DigiTickets\Integration\TescoClubcard\Api\Responses\Interfaces\UnredeemResponseInterface;
use DigiTickets\Integration\TescoClubcard\Api\Responses\Interfaces\ValidateResponseInterface;
use DigiTickets\Integration\TescoClubcard\Api\Responses\Rewards\RedeemResponse;
use DigiTickets\Integration\TescoClubcard\Api\Responses\Rewards\UnredeemResponse;
use DigiTickets\Integration\TescoClubcard\Api\Responses\Rewards\ValidateResponse;
use GuzzleHttp\Client;

class TescoClubcardRewardsRequest extends AbstractTescoClubcardRequest
{
    public function getUrl(): string
    {
        return 'https://clubcard.api.tesco.com/v1.0/TokenProcessorService/';
    }

    public function getAuthKey()
    {
        return 'appKeyToken='.$this->getAppKeyToken().'&appKey='.$this->getAppKey();
    }

    private function send(AbstractMessage $message): \stdClass
    {
        error_log('Sending a Reward request');
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
        error_log('Rewards $response: '.var_export($response, true));

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

    public function validate(string $voucherNumber): ValidateResponseInterface
    {
        error_log('/');
        error_log('validate voucher (Rewards): '.$voucherNumber);
        $message = new ValidateMessage($voucherNumber);
        //error_log('Validate $message: '.var_export($message, true));
        return new ValidateResponse($this->send($message));
    }

    public function redeem(string $voucherNumber): RedeemResponseInterface
    {
        return new RedeemResponse(json_decode('{"dummy":"true"}'));
    }

    public function unredeem(string $voucherNumber): UnredeemResponseInterface
    {
        return new UnredeemResponse(json_decode('{"dummy":"true"}'));
    }
}
