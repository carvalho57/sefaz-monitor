<?php

namespace App\Controller;

use App\Model\WebServiceConfig;
use App\Service\SefazCommunicator;
use Framework\Config;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\View;

class MonitorController
{
    private SefazCommunicator $sefazCommunicator;
    public function __construct()
    {
        $webserviceConfig = new WebServiceConfig(
            Config::get('ENVIRONMENT'),
            Config::get('CERTIFICATE_KEY_PEM_PATH'),
            Config::get('CERTIFICATE_CLIENT_PEM_PATH'),
            Config::get('UF'),
            Config::get('WEBSERVICE_VERSION')
        );

        $this->sefazCommunicator = new SefazCommunicator($webserviceConfig);
    }

    public function index(Request $request): View
    {
        return View::make('home/index');
    }

    public function status(Request $request)
    {
        $uf       = $request->getParams['uf'] ?? Config::get('UF');
        $statusUF = $this->sefazCommunicator->getStatusUf($uf);

        return (new Response($statusUF))
                                ->addHeader('Content-Type', 'text/xml');
    }
}
