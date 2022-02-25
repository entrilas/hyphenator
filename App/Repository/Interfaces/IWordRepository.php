<?php

namespace App\Repository\Interfaces;

use App\Models\Word;

interface IWordRepository
{
    public function getWords(): array;
    public function getWord(int $id): Word|bool;
    public function getWordByName(string $word): Word|bool;
    public function submitWord(string $word, string $hyphenatedWord): bool;
    public function deleteWord(int $id): bool;
    public function updateWord(int $id, string $word, string $hyphenatedWord): Word|bool;
}