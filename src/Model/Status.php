<?php

declare(strict_types=1);

namespace App\Model;

use Framework\Database\DB;

class Status implements \Stringable
{
    public const ATIVO = 107;
    //“108-Serviço Paralisado Temporariamente”
    public const PARALISADO_TEMPORARIAMENTE = 108;
    //"109-Serviço Paralisado sem Previsão”.
    public const PARALISADO_SEM_PREVISAO = 109;

    public readonly int $code;
    public readonly string $description;
    public readonly string $state;
    public readonly string $stateName;
    public readonly int $averageResponseTime;
    public readonly ?\DateTimeImmutable $expectedReturn;
    public readonly string $observation;
    public readonly ?\DateTimeImmutable $updatedTime;

    public function __construct(int $code, string $description, string $state, string $stateName, int $averageResponseTime, ?\DateTimeImmutable $expectedReturn = null, string $observation = '', ?\DateTimeImmutable $updatedTime = null)
    {
        $this->state               = strtoupper($state);
        $this->stateName           = mb_strtoupper($stateName);
        $this->code                = $code;
        $this->description         = $description;
        $this->averageResponseTime = $averageResponseTime;
        $this->expectedReturn      = $expectedReturn;
        $this->observation         = $observation;
        $this->updatedTime         = $updatedTime;
    }

    public function save()
    {
        //https://www.php.net/manual/en/pdo.prepare.php cannot use named parameter more tthan once in a prepared statement, unless emulation mode is on
        $query = <<<SQL
            INSERT INTO
            State (STATE, STATE_NAME, STATUS_CODE, STATUS_DESCRIPTION, AVERAGE_RESPONSE, EXPECTED_RETURN, OBSERVATION, UPDATED_TIME)
            VALUES (:state, :state_name, :status_code, :status_description, :average_reponse, :expected_return, :observation, NOW())
            ON DUPLICATE KEY UPDATE 
                STATUS_CODE = VALUES(STATUS_CODE),
                STATUS_DESCRIPTION = VALUES(STATUS_DESCRIPTION),
                AVERAGE_RESPONSE = VALUES(AVERAGE_RESPONSE),
                EXPECTED_RETURN = VALUES(EXPECTED_RETURN),
                OBSERVATION = VALUES(OBSERVATION),
                UPDATED_TIME = NOW();
        SQL;


        $db        = DB::getInstance();
        $statement = $db->prepare($query);


        $statement->execute([
            ':state'              => $this->state,
            ':state_name'         => $this->stateName,
            ':status_code'        => $this->code,
            ':status_description' => $this->description,
            ':average_reponse'    => $this->averageResponseTime,
            ':expected_return'    => $this->expectedReturn?->format('Y-m-d h:m:s') ?? null,
            ':observation'        => $this->observation                            ?? null,
        ]);
    }

    public static function getByState(string $state): ?static
    {
        $state = strtoupper($state);

        $query = 'SELECT ID, STATE, STATE_NAME, STATUS_CODE, STATUS_DESCRIPTION, AVERAGE_RESPONSE, EXPECTED_RETURN, OBSERVATION, UPDATED_TIME 
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

    public static function getAll(): array
    {
        $query = <<<SQL
            SELECT ID,
                STATE,
                STATE_NAME,
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
        return $result->fetchAll();
    }

    public static function createFromRow(array $row): static
    {
        $status = new static(
            (int)$row['STATUS_CODE'],
            $row['STATUS_DESCRIPTION'],
            $row['STATE'],
            $row['STATE_NAME'],
            (int)$row['AVERAGE_RESPONSE'],
            new \DateTimeImmutable($row['EXPECTED_RETURN']),
            $row['OBSERVATION'],
            new \DateTimeImmutable($row['UPDATED_TIME']),
        );

        return $status;
    }
    public function __toString(): string
    {
        return json_encode([
            'state'               => $this->state,
            'stateName'           => $this->stateName,
            'code'                => $this->code,
            'description'         => $this->description,
            'averageResponseTime' => $this->averageResponseTime,
        ], JSON_PRETTY_PRINT);
    }
}
