<?php

declare(strict_types=1);

namespace App\Service;

use App\Communication\SefazCommunicator;
use App\Exception\CannotResolveWebServiceException;
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

    public function getStatusForUF($uf): Status
    {
        $response = $this->communicator->getStatusUf($uf);

        $retConsStatServ = json_decode(json_encode(simplexml_load_string($response)));

        if (!in_array($retConsStatServ->cStat, [Status::ATIVO, Status::PARALISADO_SEM_PREVISAO, Status::PARALISADO_TEMPORARIAMENTE])) {
            //Todo Tratar rejeição
        }

        $expectedReturn = null;
        if (!empty($retConsStatServ->dhRetorno)) {
            $expectedReturn = new \DateTimeImmutable($retConsStatServ->dhRetorno);
        }

        $status                      = new Status(
            (int)$retConsStatServ->cStat,
            $retConsStatServ->xMotivo,
            $uf,
            State::getStateName($uf),
            (int)($retConsStatServ->tMed ?? 1),
            $expectedReturn,
            $retConsStatServ->xObs      ?? '',
            new \DateTimeImmutable($retConsStatServ->dhRecbto)
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
            } catch(CannotResolveWebServiceException) {
                continue;
            }

            $webserviceStatus->save();

            if ($countUFs === 1) {
                continue;
            }

            foreach ($ufs as $uf) {
                $status = new Status(
                    $webserviceStatus->code,
                    $webserviceStatus->description,
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
