<?php

declare(strict_types=1);

namespace App\Repository;

use App\Constants\Constants;
use App\Core\Connection;
use App\Core\Database\QueryBuilder;
use App\Models\Pattern;
use App\Repository\Interfaces\IPatternRepository;

class PatternRepository extends Connection implements IPatternRepository
{
    private string $table = 'patterns';

    public function __construct(QueryBuilder $queryBuilder)
    {
        parent::__construct($queryBuilder);
    }

    /**
     * @return array
     */
    public function getPatterns(): array
    {
        return $this->queryBuilder
            ->table($this->table)
            ->select(['id', 'pattern'])
            ->from()
            ->orderby(['id'])
            ->execute()
            ->getAllData();
    }

    /**
     * @param int $page
     * Patterns get query building method
     * @return array
     */
    public function getPatternsByPage(int $page): array
    {
        return $this->queryBuilder
            ->table($this->table)
            ->select(['id', 'pattern'])
            ->from()
            ->orderby(['id'])
            ->pagination(Constants::PAGE_LIMIT, ($page - 1) * Constants::PAGE_LIMIT)
            ->execute()
            ->getAllData();
    }

    /**
     * @param int $id
     * Pattern get by id query building method
     * @return Pattern|bool
     */
    public function getPattern(int $id): Pattern|bool
    {
        $patternArray = $this->queryBuilder
            ->table($this->table)
            ->select(['id', 'pattern'])
            ->from()
            ->where('id')
            ->execute([$id])
            ->getData();
        return $this->formPatternModel($patternArray);
    }

    /**
     * @param string $pattern
     * Pattern get by name query building method
     * @return Pattern|bool
     */
    public function getPatternByName(string $pattern): Pattern|bool
    {
        $patternArray = $this->queryBuilder
            ->table($this->table)
            ->select(['id', 'pattern'])
            ->from()
            ->where('pattern')
            ->execute([$pattern])
            ->getData();
        return $this->formPatternModel($patternArray);
    }

    /**
     * @param string $pattern
     * Pattern submit query building method
     * @return bool
     */
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

    /**
     * @param int $id
     * Pattern delete query building method
     * @return bool
     */
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

    /**
     * @param int    $id
     * @param string $pattern
     * Pattern update query building method
     * @return Pattern|bool
     */
    public function updatePattern(int $id, string $pattern): Pattern|bool
    {
        $patternArray = $this->queryBuilder
            ->table($this->table)
            ->update()
            ->set(['pattern'])
            ->where('id')
            ->execute([$pattern, $id])
            ->getData();
        return $this->formPatternModel($patternArray);
    }

    /**
     * @param array|bool $patternArray
     * Forms Data for the Pattern Model
     * @return Pattern|bool
     */
    private function formPatternModel(array|bool $patternArray): Pattern|bool
    {
        if ($patternArray !== false) {
            return new Pattern((int)$patternArray['id'], $patternArray['pattern']);
        }
        return false;
    }
}
