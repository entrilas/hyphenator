<?php

namespace App\Repository;

use App\Constants\Constants;
use App\Core\Connection;
use App\Core\Database\QueryBuilder;
use App\Models\Word;
use App\Repository\Interfaces\IWordRepository;

class WordRepository extends Connection implements IWordRepository
{
    private string $table = 'words';

    public function __construct(
        QueryBuilder $queryBuilder,
        private DataFormer $dataFormer
    ) {
        parent::__construct($queryBuilder);
    }

    /**
     * @return array
     */
    public function getWords(): array
    {
        return $this->queryBuilder
            ->table($this->table)
            ->select(['id', 'word', 'hyphenated_word'])
            ->from()
            ->orderby(['id'])
            ->execute()
            ->getAllData();
    }

    /**
     * @param int $page
     *
     * @return array
     */
    public function getWordsByPage(int $page): array
    {
        return $this->queryBuilder
            ->table($this->table)
            ->select(['id', 'word', 'hyphenated_word'])
            ->from()
            ->orderby(['id'])
            ->pagination(Constants::PAGE_LIMIT, ($page - 1) * Constants::PAGE_LIMIT)
            ->execute()
            ->getAllData();
    }

    /**
     * @param int $id
     *
     * @return Word|bool
     */
    public function getWord(int $id): Word|bool
    {
        $wordsArray = $this->queryBuilder
            ->table($this->table)
            ->select(['id', 'word', 'hyphenated_word'])
            ->from()
            ->where('id')
            ->execute([$id])
            ->getData();
        return $this->dataFormer->formWordModel($wordsArray);
    }

    /**
     * @param string $word
     *
     * @return Word|bool
     */
    public function getWordByName(string $word): Word|bool
    {
        $wordsArray = $this->queryBuilder
            ->table($this->table)
            ->select(['id', 'word', 'hyphenated_word'])
            ->from()
            ->where('word')
            ->execute([$word])
            ->getData();
        return $this->dataFormer->formWordModel($wordsArray);
    }

    /**
     * @param string $word
     * @param string $hyphenatedWord
     *
     * @return bool
     */
    public function submitWord(string $word, string $hyphenatedWord): bool
    {
        return $this->queryBuilder
            ->table($this->table)
            ->insert()
            ->columns(['word', 'hyphenated_word'])
            ->values([$word, $hyphenatedWord])
            ->execute([$word, $hyphenatedWord])
            ->getData();
    }

    /**
     * @param int $id
     *
     * @return bool
     */
    public function deleteWord(int $id): bool
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
     * @param string $word
     * @param string $hyphenatedWord
     *
     * @return Word|bool
     */
    public function updateWord(int $id, string $word, string $hyphenatedWord): Word|bool
    {
        $wordsArray = $this->queryBuilder
            ->table($this->table)
            ->update()
            ->set(['word', 'hyphenated_word'])
            ->where('id')
            ->execute([$word, $hyphenatedWord, $id])
            ->getData();
        return $this->dataFormer->formWordModel($wordsArray);
    }
}
