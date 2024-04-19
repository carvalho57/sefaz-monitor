<?php

declare(strict_types=1);

namespace App\Service;

use App\Communication\SefazCommunicator;
use App\Exception\CannotResolveWebServiceException;
use App\Exception\WebServiceRejectException;
use App\Helper\Xml;
use App\Model\State;
use App\Model\Status;
use App\Model\WebServiceInfo;

class StatusService
{
    private SefazCommunicator $communicator;

    public function __construct(SefazCommunicator $sefazCommunicator)
    {
        $this->communicator = $sefazCommunicator;
    }

    /**
     * Undocumented function
     *
     * @param [type] $uf
     * @return Status
     *
     * @throws WebServiceRejectException
     * @throws CannotResolveWebServiceException
     */
    public function getStatusForUF($uf): Status
    {
        $response = $this->communicator->getStatusUf($uf);

        $xml             = new Xml($response);
        $retConsStatServ = $xml->toStd();

        $statusCode           = (int)$retConsStatServ->cStat;
        $statusDescription    = $retConsStatServ->xMotivo;

        if (!in_array($statusCode, [Status::ATIVO, Status::PARALISADO_SEM_PREVISAO, Status::PARALISADO_TEMPORARIAMENTE])) {
            throw new WebServiceRejectException($statusDescription, $statusCode);
        }

        $inContigency = false;
        if ($statusCode != Status::ATIVO) {
            $contingencyResponse = $this->communicator->getStatusUf(uf: $uf, contingency: true);

            $xml                       = new Xml($contingencyResponse);
            $contigencyRetConsStatServ = $xml->toStd();


            if ($contigencyRetConsStatServ->cStat == Status::ATIVO) {
                $inContigency    = true;
                $retConsStatServ = $contigencyRetConsStatServ;
            }
        }

        $expectedReturn = null;

        if (!empty($retConsStatServ->dhRetorno)) {
            $expectedReturn = new \DateTimeImmutable($retConsStatServ->dhRetorno);
        }

        $status = Status::create(
            (int)$retConsStatServ->cStat,
            $uf,
            State::getStateName($uf),
            (int)($retConsStatServ->tMed ?? 1),
            $expectedReturn,
            $retConsStatServ->xObs      ?? '',
            new \DateTimeImmutable($retConsStatServ->dhRecbto),
            $inContigency
        );

        return $status;
    }

    //TODO Esse processo tem que rodar em background
    public function updateAllUF()
    {
        /*
            * Não é necessário processar todas as UF, pois alguns webservices
            atendem a múltiplas UFs. Portanto, a execução deve ser feita para cada webservice, e não para cada UF.

            * Quanto ao consumo indevido, não será um problema, pois estamos lidando com webservices distintos.
            Não enviaremos mais de uma requisição para o mesmo webservice, evitando assim qualquer violação de limites de uso.
        */


        foreach (WebServiceInfo::getAuthorizes() as $authorize) {
            $ufs = WebServiceInfo::getUFsForAuthorize($authorize);

            $countUFs    = count($ufs);
            $firstUF     = array_shift($ufs);

            try {
                $webserviceStatus = $this->getStatusForUF($firstUF);
            } catch(\Throwable) {
                continue;
            }

            $webserviceStatus->save();

            if ($countUFs === 1) {
                continue;
            }

            foreach ($ufs as $uf) {
                $status = Status::create(
                    $webserviceStatus->webserviceStatusCode,
                    $uf,
                    State::getStateName($uf),
                    $webserviceStatus->averageResponseTime,
                    $webserviceStatus->expectedReturn,
                    $webserviceStatus->observation,
                    $webserviceStatus->updatedTime
                );

                $status->save();
            }
        }
    }
}
