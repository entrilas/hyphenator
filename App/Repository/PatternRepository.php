<?php

declare(strict_types=1);

namespace App\Repository;

use App\Core\Connection;
use App\Core\Database\QueryBuilder;
use App\Models\Pattern;
use App\Repository\Interfaces\IPatternRepository;

class PatternRepository extends Connection implements IPatternRepository
{
    private string $table = 'patterns';

    public function __construct(QueryBuilder $queryBuilder) {
        parent::__construct($queryBuilder);
    }

    public function getPatterns(): array
    {
        return $this->queryBuilder
            ->table($this->table)
            ->select(['id', 'pattern'])
            ->from()
            ->execute()
            ->getAllData();
    }

    public function getPattern(int $id): Pattern|bool
    {
        $patternArray = $this->queryBuilder
            ->table($this->table)
            ->select(['id', 'pattern'])
            ->from()
            ->where('id')
            ->execute([$id])
            ->getData();
        if($patternArray !== false){
            return new Pattern((int)$patternArray['id'], $patternArray['pattern']);
        }
        return false;
    }

    public function getPatternByName(string $pattern): Pattern|bool
    {
        $patternArray = $this->queryBuilder
            ->table($this->table)
            ->select(['id', 'pattern'])
            ->from()
            ->where('pattern')
            ->execute([$pattern])
            ->getData();
        if($patternArray !== false){
            return new Pattern((int)$patternArray['id'], $patternArray['pattern']);
        }
        return false;
    }

    public function submitPattern(string $pattern): bool
    {
        return $this->queryBuilder
            ->table($this->table)
            ->insert()
            ->columns(['pattern'])
            ->values([$pattern])
            ->execute([$pattern])
            ->getData();
    }

    public function deletePattern(int $id): bool
    {
        return $this->queryBuilder
            ->table($this->table)
            ->delete()
            ->from()
            ->where('id')
            ->execute([$id])
            ->getData();
    }

    public function updatePattern(int $id, string $pattern): Pattern|bool
    {
        $patternArray = $this->queryBuilder
            ->table($this->table)
            ->update()
            ->set(['pattern'])
            ->where('id')
            ->execute([$pattern, $id])
            ->getData();
        if($patternArray !== false){
            return new Pattern($id, $pattern);
        }
        return false;
    }
}
