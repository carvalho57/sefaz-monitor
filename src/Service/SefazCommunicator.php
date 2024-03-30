<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\WebServiceConfig;
use App\Model\WebServiceInfo;

class SefazCommunicator
{
    private SoapCurl $soap;

    public function __construct(private WebServiceConfig $config)
    {
        $this->soap = new SoapCurl($config->certificateClientPEMPath, $config->certificateKeyPEMPath);
    }

    public function getStatusUf(string $uf, string $environment = '')
    {
        $method      = 'nfeStatusServico';
        $environment = $environment ?: $this->config->environment;
        $ufCode      = $this->getUfCode($uf);


        $wsInfo = $this->resolveService($uf, $method, $this->config->version, $environment);

        $message = <<<CONSTSTATSERV
            <consStatServ xmlns="{$wsInfo->urlPortal}" versao="{$wsInfo->version}">
                <tpAmb>{$environment}</tpAmb>
                <cUF>{$ufCode}</cUF>
                <xServ>STATUS</xServ>
            </consStatServ>
        CONSTSTATSERV;

        return $this->soap->send($wsInfo, $message);
    }

    public function resolveService(string $uf, string $method, string $version, string $environment): WebServiceInfo
    {
        $authorizeEnvironment = $this->getAuthorizeEnvironment($uf);

        $webservices = $this->loadWebserviceRelation();

        if (!isset($webservices[$authorizeEnvironment][$version][$method][$environment])) {
            throw new \RuntimeException('Cannot resolve the webservice for the provide informations');
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

    private function getAuthorizeEnvironment(string $uf)
    {
        $environments = [
            'AM' => 'AM',
            'BA' => 'BA',
            'GO' => 'GO',
            'MG' => 'MG',
            'MS' => 'MS',
            'MT' => 'MT',
            'PE' => 'PE',
            'PR' => 'PR',
            'RS' => 'RS',
            'SP' => 'SP',
            'MA' => 'SVAN',
            'PA' => 'SVAN',
            'AC' => 'SVRS',
            'AL' => 'SVRS',
            'AP' => 'SVRS',
            'CE' => 'SVRS',
            'DF' => 'SVRS',
            'ES' => 'SVRS',
            'PB' => 'SVRS',
            'PI' => 'SVRS',
            'RJ' => 'SVRS',
            'RN' => 'SVRS',
            'RO' => 'SVRS',
            'RR' => 'SVRS',
            'SC' => 'SVRS',
            'SE' => 'SVRS',
            'TO' => 'SVRS',
        ];


        return $environments[$uf];
    }

    private function getUfCode(string $uf): string
    {
        $ufCode = [
            'AC' => '12',
            'AL' => '27',
            'AP' => '16',
            'AM' => '13',
            'BA' => '29',
            'CE' => '23',
            'DF' => '53',
            'ES' => '32',
            'GO' => '52',
            'MA' => '21',
            'MT' => '51',
            'MS' => '50',
            'MG' => '31',
            'PA' => '15',
            'PB' => '25',
            'PR' => '41',
            'PE' => '26',
            'PI' => '22',
            'RJ' => '33',
            'RN' => '24',
            'RS' => '43',
            'RO' => '11',
            'RR' => '14',
            'SC' => '42',
            'SP' => '35',
            'SE' => '28',
            'TO' => '17',
        ];

        return $ufCode[$uf];
    }
    private function loadWebserviceRelation(): array
    {
        //Homologation
        $webservices['PR']['4.00']['nfeStatusServico']['2'] = [
            'url'       => 'https://homologacao.nfe.sefa.pr.gov.br/nfe/NFeStatusServico4',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['SVRS']['4.00']['nfeStatusServico']['2'] = [
            'url'       => 'https://nfe-homologacao.svrs.rs.gov.br/ws/NfeStatusServico/NfeStatusServico4.asmx',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];


        //Production
        $webservices['PR']['4.00']['nfeStatusServico']['1'] = [
            'url'       => 'https://nfe.sefa.pr.gov.br/nfe/NFeStatusServico4',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['SVRS']['4.00']['nfeStatusServico']['1'] = [
            'url'       => 'https://nfe-homologacao.svrs.rs.gov.br/ws/NfeStatusServico/NfeStatusServico4.asmx',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        return $webservices;
    }

    public static function getUFs()
    {
        return [
            'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA',
            'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO',
            'RR', 'SC', 'SP', 'SE', 'TO',
        ];
    }
}
