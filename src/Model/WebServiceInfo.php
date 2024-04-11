<?php

namespace App\Model;

class WebServiceInfo
{
    private static array $environments = [
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

    public function __construct(
        public readonly string $soapAction,
        public readonly string $urlPortal,
        public readonly string $urlService,
        public readonly string $urlNamespace,
        public readonly string $version,
    ) {
    }

    public static function getUFsForAuthorize(string $authorize)
    {
        return array_keys(self::$environments, strtoupper($authorize));
    }

    public static function getAuthorizes()
    {
        return array_unique(array_values(self::$environments));
    }

    public static function getAuthorizeEnvironmentByUF(string $uf)
    {
        //TODO caso não exista lançar excepetion
        if (!array_key_exists($uf, self::$environments)) {
            throw new \InvalidArgumentException("UF {$uf} dont exist");
        }

        return self::$environments[strtoupper($uf)];
    }

    public static function getRelations(): array
    {
        // HOMOLOGATION
        $webservices['AM']['4.00']['nfeStatusServico']['2'] = [
            'url'       => 'https://homnfe.sefaz.am.gov.br/services2/services/NfeStatusServico4',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['BA']['4.00']['nfeStatusServico']['2'] = [
            'url'       => 'https://hnfe.sefaz.ba.gov.br/webservices/NFeStatusServico4/NFeStatusServico4.asmx',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['GO']['4.00']['nfeStatusServico']['2'] = [
            'url'       => 'https://homolog.sefaz.go.gov.br/nfe/services/NFeStatusServico4',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['MG']['4.00']['nfeStatusServico']['2'] = [
            'url'       => 'https://hnfe.fazenda.mg.gov.br/nfe2/services/NFeStatusServico4',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['MS']['4.00']['nfeStatusServico']['2'] = [
            'url'       => 'https://hom.nfe.sefaz.ms.gov.br/ws/NFeStatusServico4',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['MT']['4.00']['nfeStatusServico']['2'] = [
            'url'       => 'https://homologacao.sefaz.mt.gov.br/nfews/v2/services/NfeStatusServico4',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['PE']['4.00']['nfeStatusServico']['2'] = [
            'url'       => 'https://nfehomolog.sefaz.pe.gov.br/nfe-service/services/NFeStatusServico4',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['PR']['4.00']['nfeStatusServico']['2'] = [
            'url'       => 'https://homologacao.nfe.sefa.pr.gov.br/nfe/NFeStatusServico4',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['RS']['4.00']['nfeStatusServico']['2'] = [
            'url'       => 'https://nfe-homologacao.sefazrs.rs.gov.br/ws/NfeStatusServico/NfeStatusServico4.asmx',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['SP']['4.00']['nfeStatusServico']['2'] = [
            'url'       => 'https://homologacao.nfe.fazenda.sp.gov.br/ws/nfestatusservico4.asmx',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['SVAN']['4.00']['nfeStatusServico']['2'] = [
            'url'       => 'https://hom.sefazvirtual.fazenda.gov.br/NFeStatusServico4/NFeStatusServico4.asmx',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['SVRS']['4.00']['nfeStatusServico']['2'] = [
            'url'       => 'https://nfe-homologacao.svrs.rs.gov.br/ws/NfeStatusServico/NfeStatusServico4.asmx',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        // PRODUCTION


        $webservices['AM']['4.00']['nfeStatusServico']['1'] = [
            'url'       => 'https://nfe.sefaz.am.gov.br/services2/services/NfeStatusServico4',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['BA']['4.00']['nfeStatusServico']['1'] = [
            'url'       => 'https://nfe.sefaz.ba.gov.br/webservices/NFeStatusServico4/NFeStatusServico4.asmx',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['GO']['4.00']['nfeStatusServico']['1'] = [
            'url'       => 'https://nfe.sefaz.go.gov.br/nfe/services/NFeStatusServico4',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['MG']['4.00']['nfeStatusServico']['1'] = [
            'url'       => 'https://nfe.fazenda.mg.gov.br/nfe2/services/NFeStatusServico4',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['MS']['4.00']['nfeStatusServico']['1'] = [
            'url'       => 'https://nfe.sefaz.ms.gov.br/ws/NFeStatusServico4',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['MT']['4.00']['nfeStatusServico']['1'] = [
            'url'       => 'https://nfe.sefaz.mt.gov.br/nfews/v2/services/NfeStatusServico4',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['PE']['4.00']['nfeStatusServico']['1'] = [
            'url'       => 'https://nfe.sefaz.pe.gov.br/nfe-service/services/NFeStatusServico4',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['PR']['4.00']['nfeStatusServico']['1'] = [
            'url'       => 'https://nfe.sefa.pr.gov.br/nfe/NFeStatusServico4',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['RS']['4.00']['nfeStatusServico']['1'] = [
            'url'       => 'https://nfe.sefazrs.rs.gov.br/ws/NfeStatusServico/NfeStatusServico4.asmx',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['SP']['4.00']['nfeStatusServico']['1'] = [
            'url'       => 'https://nfe.fazenda.sp.gov.br/ws/nfestatusservico4.asmx',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['SVAN']['4.00']['nfeStatusServico']['1'] = [
            'url'       => 'https://www.sefazvirtual.fazenda.gov.br/NFeStatusServico4/NFeStatusServico4.asmx',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        $webservices['SVRS']['4.00']['nfeStatusServico']['1'] = [
            'url'       => 'https://nfe.svrs.rs.gov.br/ws/NfeStatusServico/NfeStatusServico4.asmx',
            'service'   => 'NFeStatusServico4',
            'operation' => 'nfeStatusServicoNF',
        ];

        return $webservices;
    }
}
