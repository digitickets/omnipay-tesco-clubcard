<?php

namespace DigiTickets\TescoClubcard;

use DigiTickets\TescoClubcard\Messages\AbstractMessage;
use DigiTickets\TescoClubcard\Messages\Uk\Omnipay\PurchaseRequest;
use DigiTickets\TescoClubcard\Messages\Uk\Omnipay\RefundRequest;
use DigiTickets\TescoClubcard\Messages\Uk\Voucher\RedeemRequest;
use DigiTickets\TescoClubcard\Messages\Uk\Voucher\UnredeemRequest;
use DigiTickets\TescoClubcard\Messages\Uk\Voucher\ValidateRequest;
use DigiTickets\TescoClubcard\Responses\Uk\RedeemResponse;
use DigiTickets\TescoClubcard\Responses\Uk\UnredeemResponse;
use DigiTickets\TescoClubcard\Responses\Uk\ValidateResponse;
use GuzzleHttp\Client;
use Omnipay\Common\Message\AbstractRequest;

class UkGateway extends AbstractTescoClubcardGateway
{
    public function getName()
    {
        return 'Tesco Clubcard Rewards';
    }

    protected function createRequest($class, array $parameters)
    {
        $parameters['gateway'] = $this;

        return parent::createRequest($class, $parameters);
    }

    public function authorize(array $parameters = array())
    {
        return $this->createRequest(AuthorizeRequest::class, $parameters);
    }


    public function purchase(array $parameters = array())
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest
     */
    public function validate(array $parameters = array())
    {
        return $this->createRequest(ValidateRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest
     */
    public function redeem(array $parameters = array())
    {
        return $this->createRequest(RedeemRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest
     */
    public function unredeem(array $parameters = array())
    {
        return $this->createRequest(UnredeemRequest::class, $parameters);
    }

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

        $clientParams = [
            'base_uri' => $this->getUrl(),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => $this->getAuthKey(),
            ],
            'http_errors' => false,
        ];
        $client = new Client($clientParams);
        $params = [
            'body' => json_encode($message),
        ];
        $response = $client->post('ManageToken', $params);

        $result = json_decode($response->getBody()->getContents());

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
}
