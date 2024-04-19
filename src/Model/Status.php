<?php

declare(strict_types=1);

namespace App\Model;

use Framework\Database\DB;

class Status implements \Stringable
{
    //Webservice status
    public const ATIVO                      = 107;
    public const PARALISADO_TEMPORARIAMENTE = 108;
    public const PARALISADO_SEM_PREVISAO    = 109;
    public const SERVICO_SVC_DESATIVANDO    = 113;
    public const SERVICO_SVC_DESABILITADA   = 114;

    //internal status
    public const NORMAL       = 1;
    public const INSTABLE     = 2;
    public const STOPPED      = 3;
    public const CONTIGENCY   = 4;

    public readonly int $webserviceStatusCode;
    public readonly int $code;
    public readonly string $description;
    public readonly string $state;
    public readonly bool $inContigency;
    public readonly string $stateName;
    public readonly int $averageResponseTime;
    public readonly ?\DateTimeImmutable $expectedReturn;
    public readonly string $observation;
    public readonly ?\DateTimeImmutable $updatedTime;

    public static function create(int $webserviceStatusCode, string $state, string $stateName, int $averageResponseTime, ?\DateTimeImmutable $expectedReturn = null, string $observation = '', ?\DateTimeImmutable $updatedTime = null, bool $contigency = false)
    {
        $status                       = new Status();
        $status->state                = strtoupper($state);
        $status->stateName            = mb_strtoupper($stateName);
        $status->webserviceStatusCode = $webserviceStatusCode;
        $status->description          = match ($webserviceStatusCode) {
            self::ATIVO                                                     => 'Serviço em Operação',
            self::PARALISADO_TEMPORARIAMENTE                                => 'Serviço Paralisado Temporariamente',
            self::PARALISADO_SEM_PREVISAO                                   => 'Serviço Paralisado Sem Previsão',
            self::ATIVO && $contigency                                      => 'Serviço SVC em Operação',
            self::SERVICO_SVC_DESATIVANDO || self::SERVICO_SVC_DESABILITADA => 'SVC em processo de desativação',
            default                                                         => ''
        };

        $status->code = match ($webserviceStatusCode) {
            self::ATIVO                                                      => self::NORMAL,
            self::PARALISADO_TEMPORARIAMENTE                                 => self::INSTABLE,
            self::PARALISADO_SEM_PREVISAO || self::SERVICO_SVC_DESABILITADA  => self::STOPPED,
            self::ATIVO && $contigency                                       => self::CONTIGENCY,
            default                                                          => ''
        };
        $status->averageResponseTime = $averageResponseTime;
        $status->expectedReturn      = $expectedReturn;
        $status->observation         = $observation;
        $status->updatedTime         = $updatedTime;

        return $status;
    }

    public function save()
    {
        //https://www.php.net/manual/en/pdo.prepare.php cannot use named parameter more tthan once in a prepared statement, unless emulation mode is on
        $query = <<<SQL
            INSERT INTO
            State (STATE, STATE_NAME,STATUS_WEBSERVICE, STATUS_CODE, STATUS_DESCRIPTION, AVERAGE_RESPONSE, EXPECTED_RETURN, OBSERVATION, UPDATED_TIME)
            VALUES (:state, :state_name, :status_webservice, :status_code, :status_description, :average_reponse, :expected_return, :observation, NOW())
            ON DUPLICATE KEY UPDATE 
                STATUS_CODE = VALUES(STATUS_CODE),
                STATUS_WEBSERVICE = VALUES(STATUS_WEBSERVICE),
                STATUS_DESCRIPTION = VALUES(STATUS_DESCRIPTION),
                AVERAGE_RESPONSE = VALUES(AVERAGE_RESPONSE),
                EXPECTED_RETURN = VALUES(EXPECTED_RETURN),
                OBSERVATION = VALUES(OBSERVATION),
                UPDATED_TIME = NOW();
        SQL;


        $db        = DB::getInstance();
        $statement = $db->prepare($query);


        $statement->execute([
            ':state'               => $this->state,
            ':state_name'          => $this->stateName,
            ':status_code'         => $this->code,
            ':status_webservice'   => $this->webserviceStatusCode,
            ':status_description'  => $this->description,
            ':average_reponse'     => $this->averageResponseTime,
            ':expected_return'     => $this->expectedReturn?->format('Y-m-d h:m:s') ?? null,
            ':observation'         => $this->observation                            ?? null,
        ]);
    }

    public static function getByState(string $state): ?static
    {
        $state = strtoupper($state);

        $query = 'SELECT ID, STATE, STATE_NAME, STATUS_WEBSERVICE, STATUS_CODE, STATUS_DESCRIPTION, AVERAGE_RESPONSE, EXPECTED_RETURN, OBSERVATION, UPDATED_TIME 
                    FROM State 
                    WHERE State = :state';

        $db        = DB::getInstance();
        $statement = $db->prepare($query);
        $statement->execute([':state' => $state]);

        if ($statement->rowCount() == 0) {
            return null;
        }
        $row = $statement->fetch();
        return static::createFromRow($row);
    }

    /**
     * Get all
     *
     * @return Status[]
     */
    public static function getAll(): array
    {
        $query = <<<SQL
            SELECT ID,
                STATE,
                STATE_NAME,
                STATUS_WEBSERVICE,
                STATUS_CODE,
                STATUS_DESCRIPTION,
                AVERAGE_RESPONSE,
                EXPECTED_RETURN,
                OBSERVATION,
                UPDATED_TIME 
            FROM State
        SQL;

        $db        = DB::getInstance();

        $result = $db->query($query);
        return array_map(fn ($row) => Status::createFromRow($row), $result->fetchAll());
    }

    public static function createFromRow(array $row): static
    {
        $status = new static();

        $status->state                = $row['STATE'];
        $status->stateName            = $row['STATE_NAME'];
        $status->code                 = (int)$row['STATUS_CODE'];
        $status->webserviceStatusCode = (int)$row['STATUS_WEBSERVICE'];
        $status->description          = $row['STATUS_DESCRIPTION'];
        $status->averageResponseTime  = (int)$row['AVERAGE_RESPONSE'];
        $status->expectedReturn       = $row['EXPECTED_RETURN'] ? new \DateTimeImmutable($row['EXPECTED_RETURN']) : null;
        $status->observation          = $row['OBSERVATION'];
        $status->updatedTime          = $row['UPDATED_TIME'] ? new \DateTimeImmutable($row['UPDATED_TIME']) : null;

        return $status;
    }

    public function toArray(): array
    {
        return [
            'state'                                          => $this->state,
            'stateName'                                      => $this->stateName,
            'webserviceCode'                                 => $this->webserviceStatusCode,
            'status'                                         => $this->code,
            'statusDescription'                              => match ($this->code) {
                self::NORMAL     => 'normal',
                self::INSTABLE   => 'instable',
                self::STOPPED    => 'stopped',
                self::CONTIGENCY => 'contingency',
                default          => ''
            },
            'description'                             => $this->description,
            'averageResponseTime'                     => $this->averageResponseTime,
            'contigency'                              => $this->code === self::CONTIGENCY,
        ];
    }

    public function __toString(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }
}
