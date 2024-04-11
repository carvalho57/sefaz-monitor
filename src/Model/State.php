<?php

namespace App\Model;

class State
{
    public static $stateInfo = [
        ['state' => 'AC', 'code' => '12', 'name' => 'Acre'],
        ['state' => 'AL', 'code' => '27', 'name' => 'Alagoas'],
        ['state' => 'AP', 'code' => '16', 'name' => 'Amapá'],
        ['state' => 'AM', 'code' => '13', 'name' => 'Amazonas'],
        ['state' => 'BA', 'code' => '29', 'name' => 'Bahia'],
        ['state' => 'CE', 'code' => '23', 'name' => 'Ceará'],
        ['state' => 'DF', 'code' => '53', 'name' => 'Distrito Federal'],
        ['state' => 'ES', 'code' => '32', 'name' => 'Espírito Santo'],
        ['state' => 'GO', 'code' => '52', 'name' => 'Goiás'],
        ['state' => 'MA', 'code' => '21', 'name' => 'Maranhão'],
        ['state' => 'MT', 'code' => '51', 'name' => 'Mato Grosso'],
        ['state' => 'MS', 'code' => '50', 'name' => 'Mato Grosso do Sul'],
        ['state' => 'MG', 'code' => '31', 'name' => 'Minas Gerais'],
        ['state' => 'PA', 'code' => '15', 'name' => 'Pará'],
        ['state' => 'PB', 'code' => '25', 'name' => 'Paraíba'],
        ['state' => 'PR', 'code' => '41', 'name' => 'Paraná'],
        ['state' => 'PE', 'code' => '26', 'name' => 'Pernambuco'],
        ['state' => 'PI', 'code' => '22', 'name' => 'Piauí'],
        ['state' => 'RJ', 'code' => '33', 'name' => 'Rio de Janeiro'],
        ['state' => 'RN', 'code' => '24', 'name' => 'Rio Grande do Norte'],
        ['state' => 'RS', 'code' => '43', 'name' => 'Rio Grande do Sul'],
        ['state' => 'RO', 'code' => '11', 'name' => 'Rondônia'],
        ['state' => 'RR', 'code' => '14', 'name' => 'Roraima'],
        ['state' => 'SC', 'code' => '42', 'name' => 'Santa Catarina'],
        ['state' => 'SP', 'code' => '35', 'name' => 'São Paulo'],
        ['state' => 'SE', 'code' => '28', 'name' => 'Sergipe'],
        ['state' => 'TO', 'code' => '17', 'name' => 'Tocantins'],
    ];

    public static function isValid(string $state): bool
    {
        return array_key_exists($state, array_column(State::$stateInfo, 'state'));
    }

    public static function getStateCode(string $state)
    {
        $states = array_column(State::$stateInfo, 'code', 'state');

        if (!array_key_exists($state, $states)) {
            throw new \InvalidArgumentException('State not found');
        }

        return $states[$state];
    }


    public static function getStateName(string $state)
    {
        $states = array_column(State::$stateInfo, 'name', 'state');

        if (!array_key_exists($state, $states)) {
            throw new \InvalidArgumentException('State not found');
        }

        return $states[$state];
    }

    public static function getAllStates()
    {
        return array_column(State::$stateInfo, 'state');
    }
}
