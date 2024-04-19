<?php

declare(strict_types=1);

namespace App\Communication;

use App\Exception\CannotResolveWebServiceException;
use App\Model\State;
use App\Model\WebServiceInfo;

class SefazCommunicator
{
    private SoapCurl $soap;

    public function __construct(private CommunicatorConfig $config)
    {
        $this->soap = new SoapCurl($config->certificateClientPEMPath, $config->certificateKeyPEMPath);
    }

    public function getStatusUf(string $uf, string $environment = '', bool $contingency = false)
    {
        $method      = 'nfeStatusServico';
        $environment = $environment ?: $this->config->environment;
        $ufCode      = State::getStateCode($uf);

        $wsInfo      = $this->resolveService($uf, $method, $this->config->version, $environment, $contingency);

        $message = <<<CONSTSTATSERV
            <consStatServ xmlns="{$wsInfo->urlPortal}" versao="{$wsInfo->version}">
                <tpAmb>{$environment}</tpAmb>
                <cUF>{$ufCode}</cUF>
                <xServ>STATUS</xServ>
            </consStatServ>
        CONSTSTATSERV;

        return $this->soap->send($wsInfo, $message);
    }

    private function resolveService(string $uf, string $method, string $version, string $environment, bool $continency): WebServiceInfo
    {
        $authorizeEnvironment = !$continency
                                    ? WebServiceInfo::getAuthorizeEnvironmentByUF($uf)
                                    : WebServiceInfo::getAuthorizeEnvironmentByUFOnContingency($uf);

        $webservices =  WebServiceInfo::getRelations();

        if (!isset($webservices[$authorizeEnvironment][$version][$method][$environment])) {
            throw new CannotResolveWebServiceException();
        }

        $webserviceInfo = $webservices[$authorizeEnvironment][$version][$method][$environment];

        $urlPortal            = 'http://www.portalfiscal.inf.br/nfe';
        $urlService           = $webserviceInfo['url'];
        $service              = $webserviceInfo['service'];
        $operation            = $webserviceInfo['operation'];

        $urlNamespace = "{$urlPortal}/wsdl/{$service}";
        $soapAction   = "{$urlNamespace}/{$operation}";

        return new WebServiceInfo($soapAction, $urlPortal, $urlService, $urlNamespace, $version);
    }
}
