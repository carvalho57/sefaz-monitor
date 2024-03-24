<?php

namespace App\Controller;

use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Http\View;

class MonitorController
{
    public function __construct()
    {
    }

    public function index(Request $request): View
    {
        return View::make('home/index');
    }

    public function status(Request $request)
    {
        return (new Response())
            ->json(
                [
                    'AC' => [
                        'name'                    => 'Acre',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',
                    ],
                    'AL' => [
                        'name'                    => 'Alagoas',
                        'status'                  => 'Ativo',
                        'description'             => 'Operando sem problemas',
                        'averageResponseTime'     => 'Médio',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nada a relatar',
                    ],
                    'AP' => [
                        'name'                    => 'Amapá',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'AM' => [
                        'name'                    => 'Amazonas',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'BA' => [
                        'name'                    => 'Bahia',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'CE' => [
                        'name'                    => 'Ceará',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'DF' => [
                        'name'                    => 'Distrito Federal',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'ES' => [
                        'name'                    => 'Espírito Santo',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'GO' => [
                        'name'                    => 'Goiás',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'MA' => [
                        'name'                    => 'Maranhão',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'MT' => [
                        'name'                    => 'Mato Grosso',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'MS' => [
                        'name'                    => 'Mato Grosso do Sul',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'MG' => [
                        'name'                    => 'Minas Gerais',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'PA' => [
                        'name'                    => 'Pará',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'PB' => [
                        'name'                    => 'Paraíba',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'PR' => [
                        'name'                    => 'Paraná',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'PE' => [
                        'name'                    => 'Pernambuco',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'PI' => [
                        'name'                    => 'Piauí',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'RJ' => [
                        'name'                    => 'Rio de Janeiro',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'RN' => [
                        'name'                    => 'Rio Grande do Norte',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'RS' => [
                        'name'                    => 'Rio Grande do Sul',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'RO' => [
                        'name'                    => 'Rondônia',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'RR' => [
                        'name'                    => 'Roraima',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'SC' => [
                        'name'                    => 'Santa Catarina',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'SP' => [
                        'name'                    => 'São Paulo',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'SE' => [
                        'name'                    => 'Sergipe',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                    'TO' => [
                        'name'                    => 'Tocantins',
                        'status'                  => 'Ativo',
                        'description'             => 'Funcionando normalmente',
                        'averageResponseTime'     => 'Baixo',
                        'returnDate'              => '22/03/2024',
                        'additionalObservations'  => 'Nenhuma',

                    ],
                ]
            );
    }
}
