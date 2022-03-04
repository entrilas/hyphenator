<?php

namespace App\Repository\Interfaces;

use App\Models\Container\PatternContainer;
use App\Models\Pattern;

interface IPatternRepository
{
    public function getPatterns(): array;
    public function getPatternsByPage(int $page): array;
    public function getPattern(int $id): Pattern|bool;
    public function getPatternByName(string $pattern): Pattern|bool;
    public function submitPattern(string $pattern): bool;
    public function deletePattern(int $id): bool;
    public function updatePattern(int $id, string $pattern): Pattern|bool;
}
