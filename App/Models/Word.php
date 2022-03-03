<?php

declare(strict_types=1);

namespace App\Models;

class Word
{
   public function __construct(
       private int $id = -1,
       private string $word,
       private string $hyphenatedWord
   ) {
   }

   public function getHyphenatedWord(): string
   {
       return $this->hyphenatedWord;
   }

   public function getWord(): string
   {
       return $this->word;
   }

   public function getId(): int
   {
       return $this->id;
   }
}
