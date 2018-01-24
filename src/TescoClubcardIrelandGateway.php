<?php

namespace DigiTickets\TescoClubcard\Requests;

use DigiTickets\TescoClubcard\Messages\AbstractMessage;
use DigiTickets\TescoClubcard\Messages\RedeemMessage;
use DigiTickets\TescoClubcard\Messages\UnredeemMessage;
use DigiTickets\TescoClubcard\Messages\ValidateMessage;
use DigiTickets\TescoClubcard\Responses\Boost\RedeemResponse;
use DigiTickets\TescoClubcard\Responses\Boost\UnredeemResponse;
use DigiTickets\TescoClubcard\Responses\Boost\ValidateResponse;
use DigiTickets\TescoClubcard\Responses\Interfaces\RedeemResponseInterface;
use DigiTickets\TescoClubcard\Responses\Interfaces\UnredeemResponseInterface;
use DigiTickets\TescoClubcard\Responses\Interfaces\ValidateResponseInterface;
use SoapClient;
use SoapVar;

class TescoClubcardIrelandGateway extends AbstractTescoClubcardGateway
{
    /**
     * @return string
     */
    public function getUrl()
    {
        return 'https://tfoag01.tescofreetime.com/TokenAuthorisationWebService/TokenAuthorise.asmx';
    }

    /**
     * @param AbstractMessage $message
     * @return \SimpleXMLElement
     */
    private function send(AbstractMessage $message)
    {
        error_log('Send...');
        // @TODO: do all the substitutions - date/time, supplier codes, etc.
        // @TODO: With tx ids, etc, should we generate them in the constructor and store them in the class?
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
        //error_log('$xml: '.$xml);

        $options['trace'] = 1;
        $wsdl = 'https://tfoag01.tescofreetime.com/TokenAuthorisationWebService/TokenAuthorise.asmx?wsdl';

        try {
            $client = new SoapClient($wsdl, $options);
            $body = new SoapVar($xml, XSD_ANYXML);
            //error_log('About to call TokenCheck()');
            // @TODO: Need to tell PHPStorm that $client has a method "TokenCheck".
            $result = $client->TokenCheck($body);
            // @TODO: Need to handle $result->TokenCheckResult being an empty string. Will need to return my own XML object.
            $resultXml = $result->TokenCheckResult;
            error_log('$result: '.var_export($resultXml, true));
            if (empty($resultXml)) {
                throw new \RuntimeException('Empty response was returned');
            }
            $responseXml = simplexml_load_string(mb_convert_encoding($resultXml, 'UTF-16'));
            //error_log('Response: TransactionResponseCode: '.$responseXml->TransactionResponseCode);
            //error_log('Response: Response status: '.$responseXml->Response->Status);
            //error_log('Last request: '.var_export($client->__getLastRequest(), true));
        } catch (\Exception $e) {
            $errorXml = <<<EOT
<?xml version="1.0" encoding="utf-16"?>
<Error message="{$e->getMessage()}"></Error>
EOT;
            $responseXml = simplexml_load_string(mb_convert_encoding($errorXml, 'UTF-16'));
        }

        return $responseXml;
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

    protected function getSourceSystem()
    {
        return $this->getThirdPartyIdentifier();
    }

    protected function getGuid()
    {
        return $this->generateGuid();
    }

    /**
     * @param string $voucherNumber
     * @return ValidateResponseInterface
     */
    public function validate($voucherNumber)
    {
        error_log('/');
        error_log('validate voucher (Boost): '.$voucherNumber);
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
        error_log('/');
        error_log('validate voucher (Boost): '.$voucherNumber);
        $message = new RedeemMessage($voucherNumber);

        //error_log('Redeem $message: '.var_export($message, true));
        return new RedeemResponse($this->send($message));
    }

    /**
     * @param string $voucherNumber
     * @return UnredeemResponseInterface
     */
    public function unredeem($voucherNumber)
    {
        error_log('/');
        error_log('validate voucher (Boost): '.$voucherNumber);
        $message = new UnredeemMessage($voucherNumber);

        //error_log('Unredeem $message: '.var_export($message, true));
        return new UnredeemResponse($this->send($message));
    }
}
