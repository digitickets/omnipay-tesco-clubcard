<?php

namespace DigiTickets\TescoClubcard\Messages\Uk\Common;

use DigiTickets\TescoClubcard\Messages\AbstractMessage;
use DigiTickets\TescoClubcard\Messages\Common\AbstractApiRequest;

abstract class AbstractUkApiRequest extends AbstractApiRequest
{
    /**
     * @return string
     */
    public function getUrl()
    {
        return 'https://clubcard.api.tesco.com/v1.0/TokenProcessorService/ManageToken';
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        /**
         * @var AbstractMessage $message
         */
        $message = $this->buildMessage();

        // Note: It is important that TokenDetailsList is an array of an array.
        $dataArray = [
            'TransactionID' => $this->generateTransactionId(),
            'TransactionDateTime' => $this->getTransactionDateTime(),
            'RequestType' => $message->getRequestType(),
            'SupplierCode' => $this->getSupplierCode(),
            'TokenDetailsList' => [
                [
                    'ReferenceNo' => '12345',
                    'RequestId' => $this->generateRequestId(),
                    'TokenCode' => $message->getVoucherNumber(),
                ],
            ],
        ];

        $dataJson = json_encode($dataArray);

        return $dataJson;
    }

    /**
     * @param mixed $data
     * @return AbstractRemoteResponse
     */
    public function sendData($data)
    {
        $headers = array(
            'Accept' => 'application/json',
            'Content-Type' => 'application/json; charset=utf-8',
            'Authorization' => sprintf('appKeyToken=%s&appKey=%s', $this->getAppKeyToken(), $this->getAppKey()),
        );
        try {
            // Allow the SSL verification to be overridden. Do NOT supply "false" as the parameter value, except when
            // you're testing this, because it is insecure.
            $sslVerification = $this->getGateway()->getSslVerification();
            if ($sslVerification) {
                $sslVerification = (
                    $sslVerification === 'true' ? true : (
                        $sslVerification === 'false' ? false : $sslVerification
                    )
                );
                $this->httpClient->setSslVerification($sslVerification);
            }

            $httpResponse = $this->httpClient->post($this->getUrl(), $headers, $data)->send()->getBody();
            $httpResponse = json_decode($httpResponse); // Decodes to stdClass.
        } catch (\Exception $e) {
            // Create a well-define object that contains the error message.
            $httpResponse = json_decode(sprintf('{"error":{"message":"%s"}}', $e->getMessage()));
        }

        // Send all the information to any listeners.
        foreach ($this->getGateway()->getListeners() as $listener) {
            $listener->update($this->getListenerAction(), $httpResponse);
        }

        return $this->response = $this->buildResponse($this, $httpResponse);
    }

    protected function getTransactionDateTime()
    {
        // Eg 07/04/2020 17:05:14
        return (new \DateTime())->format('d/m/Y h:m:s');
    }

    /**
     * @param string $appKeyToken
     */
    public function setAppKeyToken($appKeyToken)
    {
        return $this->setParameter('appKeyToken', $appKeyToken);
    }

    /**
     * @return string
     */
    public function getAppKeyToken()
    {
        return $this->getParameter('appKeyToken');
    }

    /**
     * @param string $appKey
     */
    public function setAppKey($appKey)
    {
        return $this->setParameter('appKey', $appKey);
    }

    /**
     * @return string
     */
    public function getAppKey()
    {
        return $this->getParameter('appKey');
    }
}
