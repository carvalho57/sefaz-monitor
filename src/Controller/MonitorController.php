<?php

namespace App\Controller;

use App\Communication\CommunicatorConfig;
use App\Communication\SefazCommunicator;
use App\Model\Status;
use App\Service\StatusService;
use Framework\Config;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\View;

class MonitorController
{
    private StatusService $statusService;

    public function __construct()
    {
        $sefazCommunicator = new SefazCommunicator(new CommunicatorConfig(
            Config::get('ENVIRONMENT'),
            Config::get('CERTIFICATE_KEY_PEM_PATH'),
            Config::get('CERTIFICATE_CLIENT_PEM_PATH'),
            Config::get('UF'),
            Config::get('WEBSERVICE_VERSION')
        ));

        $this->statusService = new StatusService($sefazCommunicator);
    }

    public function index(Request $request): View
    {
        return View::make('home/index');
    }

    public function update(Request $request): Response
    {
        $this->statusService->updateAllUF();

        return (new Response())->json([
            'success' => true,
            'message' => 'Atualizado com sucesso!',
        ]);
    }

    public function listAll(Request $request = null): Response
    {
        $listStatus = Status::getAll();

        $listStatus = array_map(fn (Status $status) => $status->toArray(), $listStatus);

        return(new Response())->json($listStatus);
    }

    public function status(Request $request)
    {
        $uf       = $request->getParams['uf'] ?? Config::get('UF');

        $statusUF = $this->statusService->getStatusForUF($uf);

        return(new Response())->json((string)$statusUF);
    }
}
