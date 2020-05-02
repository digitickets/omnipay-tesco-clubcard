<?php

namespace DigiTickets\TescoClubcard\Messages\Ireland\Common;

use DigiTickets\TescoClubcard\Messages\AbstractMessage;
use DigiTickets\TescoClubcard\Messages\Common\AbstractApiRequest;
use SoapClient;
use SoapVar;

abstract class AbstractIrelandApiRequest extends AbstractApiRequest
{
    /**
     * @return string
     */
    public function getUrl()
    {
        return 'https://tfoag01.tescofreetime.com/TokenAuthorisationWebService/TokenAuthorise.asmx';
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

        $xml = <<<EOT
<TokenCheck xmlns="http://TescoFreetime.co.uk/">
<ns1:message><![CDATA[<?xml version="1.0" encoding="utf-16"?>
<TokenRequest xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
    <TransactionId>{$this->generateTransactionId()}</TransactionId>
    <TransactionDateTime>{$this->getTransactionDateTime()}</TransactionDateTime>
    <Source>{$this->getSource()}</Source>
    <User />
    <Request>
        <RequestID>{$this->generateRequestId()}</RequestID>
        <RequestType>{$message->getRequestType()}</RequestType>
        <AlphaNumericID>{$message->getVoucherNumber()}</AlphaNumericID>
        <OrderNo>12345</OrderNo>
        <SupplierCode>{$this->getSupplierCode()}</SupplierCode>
    </Request>
</TokenRequest>]]></ns1:message>
<ns1:sourceSystem>{$this->getSourceSystem()}</ns1:sourceSystem>
<ns1:GUID>{$this->getGuid()}</ns1:GUID>
</TokenCheck>
EOT;

        return $xml;
    }

    /**
     * @param mixed $data
     * @return AbstractRemoteResponse
     */
    public function sendData($data)
    {
        $options['trace'] = 1;
        $wsdl = 'https://tfoag01.tescofreetime.com/TokenAuthorisationWebService/TokenAuthorise.asmx?wsdl';

        try {
            $client = new SoapClient($wsdl, $options);
            $body = new SoapVar($data, XSD_ANYXML);
            // @TODO: Need to tell PHPStorm that $client has a method "TokenCheck".
            $result = $client->TokenCheck($body);
            $resultXml = $result->TokenCheckResult;
            if (empty($resultXml)) {
                throw new \RuntimeException('Empty response was returned');
            }
            $responseXml = simplexml_load_string(mb_convert_encoding($resultXml, 'UTF-16'));
        } catch (\Exception $e) {
            $errorXml = <<<EOT
<?xml version="1.0" encoding="utf-16"?>
<Error message="{$e->getMessage()}"></Error>
EOT;
            $responseXml = simplexml_load_string(mb_convert_encoding($errorXml, 'UTF-16'));
        }

        // Send all the information to any listeners.
        foreach ($this->getGateway()->getListeners() as $listener) {
            $listener->update($this->getListenerAction(), $responseXml);
        }

        return $this->response = $this->buildResponse($this, $responseXml);
    }

    protected function getTransactionDateTime()
    {
        // Eg 2016-01-12T13:45:45.2790586+00:00
        return (new \DateTime())->format('Y-m-d\Th:m:i.uP');
    }

    protected function getSource()
    {
        return 'DigiTickets'; // Not sure if this should be client name, or something else?!
    }

    /**
     * @param string $thirdPartyIdentifier
     */
    public function setThirdPartyIdentifier($thirdPartyIdentifier)
    {
        return $this->setParameter('thirdPartyIdentifier', $thirdPartyIdentifier);
    }

    /**
     * @return string
     */
    public function getThirdPartyIdentifier()
    {
        return $this->getParameter('thirdPartyIdentifier');
    }

    protected function getSourceSystem()
    {
        return $this->getThirdPartyIdentifier();
    }

    protected function getGuid()
    {
        return $this->generateGuid();
    }
}
