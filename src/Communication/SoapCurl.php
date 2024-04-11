<?php

declare(strict_types=1);

namespace App\Communication;

use App\Model\WebserviceInfo;

class SoapCurl
{
    public function __construct(private string $keyPEMPath, private string $clientPEMPath)
    {
    }

    #TODO log communication
    public function send(
        WebserviceInfo $wsInfo,
        string $body
    ) {
        $soapMessage = $this->makeSoapMessage($wsInfo->urlNamespace, $body);

        $header   = [
            'Content-type: application/soap+xml;charset="utf-8"',
            'Accept: text/xml',
            // "SOAPAction: {$wsInfo->soapAction}", //Removed on SOAP 1.2
            'Content-length: ' . strlen($soapMessage),
        ];

        if (!empty($wsInfo->soapAction)) {
            $header[0] .= ";action=\"{$wsInfo->soapAction}\"";
        }

        error_log("urlService: $wsInfo->urlService");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $wsInfo->urlService);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $soapMessage);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSLCERT, $this->clientPEMPath);
        curl_setopt($ch, CURLOPT_SSLKEY, $this->keyPEMPath);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \RuntimeException(curl_error($ch));
        }

        if (empty($response)) {
            throw new \RuntimeException('Empty response from webservice.');
        }

        $statusCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $response   = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE));

        curl_close($ch);

        $dom               = new \DOMDocument();
        $dom->formatOutput = true;
        $dom->loadXML($response);

        $body = $dom->getElementsByTagName('Body');

        //Status code ok
        if ($statusCode !== 200) {
            if ($body->item(0)->firstElementChild->localName == 'Fault') {
                $code   = $body->item(0)->getElementsByTagName('Code')->item(0)->firstElementChild->textContent;
                $reason = $body->item(0)->getElementsByTagName('Reason')->item(0)->firstElementChild->textContent;

                throw new \RuntimeException("Code: {$code} Reason: {$reason}");
            }

            throw new \RuntimeException($response);
        }

        $nfeResultMsg = $body->item(0)->firstElementChild->firstElementChild;

        $newDocument                     = new \DOMDocument('1.0', 'UTF-8');
        $newDocument->preserveWhiteSpace = false;
        $newDocument->formatOutput       = true;
        $node                            = $newDocument->importNode($nfeResultMsg, true);
        $newDocument->appendChild($node);


        return $newDocument->saveXML();
    }

    private function extractSoapMessage(string $message)
    {
        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->loadXML($message);

        $body = $dom->getElementsByTagName('env:Body');
    }

    private function makeSoapMessage(string $urlNamespace, string $messageData)
    {
        $soapMessage = <<<SOAPMESSAGE
            <?xml version="1.0" encoding="utf-8"?>
                <env:Envelope
                    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                    xmlns:xsd="http://www.w3.org/2001/XMLSchema"
                    xmlns:env="http://www.w3.org/2003/05/soap-envelope">
                <env:Body>
                    <nfeDadosMsg xmlns="{$urlNamespace}">
                        {$messageData}
                    </nfeDadosMsg>
                </env:Body>
            </env:Envelope>
        SOAPMESSAGE;

        $soapMessage = preg_replace("/>\s*</", '><', trim($soapMessage));//Remove space between tags
        $soapMessage = preg_replace("/[\n\t\r]/", '', $soapMessage); //remove ("line-feed", "carriage return", "tab")
        $soapMessage = preg_replace("/\s{2,}/", ' ', $soapMessage); //remove insignificant spaces

        return $soapMessage;
    }
}
