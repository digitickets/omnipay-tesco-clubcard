<?php

namespace DigiTickets\TescoClubcard\Messages\Uk\Common;

use DigiTickets\TescoClubcard\Messages\AbstractMessage;
use Omnipay\Common\Message\AbstractRequest;
use SoapClient;
use SoapVar;

abstract class AbstractApiRequest extends AbstractRequest
{
    /**
     * @return string
     */
    public function getUrl()
    {
        return 'https://clubcard.api.tesco.com/v1.0/TokenProcessorService/ManageToken';
    }

    public function setGateway($value)
    {
        $this->setParameter('gateway', $value);
    }

    public function getGateway()
    {
        return $this->getParameter('gateway');
    }

    public function getVoucherCode()
    {
        return $this->getParameter('voucherCode');
    }

    public function setVoucherCode($voucherCode)
    {
        return $this->setParameter('voucherCode', $voucherCode);
    }

    /**
     * @param mixed $data
     * @return AbstractMessage
     */
    abstract protected function buildMessage();

    /**
     * @param RequestInterface $request
     * @param mixed $response
     * @return AbstractRemoteResponse
     */
    abstract protected function buildResponse($request, $response);

    /**
     * Method to return the action for the listener.
     * @return string
     */
    abstract protected function getListenerAction(): string;

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

    protected function generateGuid()
    {
        // Found this code online. Is there an alternative already in the system? Should this be made global?
        $charId = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45); // "-"
        $uuid =
            substr($charId, 0, 8).$hyphen.
            substr($charId, 8, 4).$hyphen.
            substr($charId, 12, 4).$hyphen.
            substr($charId, 16, 4).$hyphen.
            substr($charId, 20, 12);

        return $uuid;
    }

    protected function generateTransactionId()
    {
        return $this->generateGuid();
    }

    protected function generateRequestId()
    {
        return $this->generateGuid();
    }

    protected function getTransactionDateTime()
    {
        // Eg 07/04/2020 17:05:14
        return (new \DateTime())->format('d/m/Y h:m:s');
    }

    /**
     * @param string $supplierCode
     */
    public function setSupplierCode($supplierCode)
    {
        return $this->setParameter('supplierCode', $supplierCode);
    }

    /**
     * @return string
     */
    public function getSupplierCode()
    {
        return $this->getParameter('supplierCode');
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
