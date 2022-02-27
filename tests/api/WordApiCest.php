<?php

use Codeception\Example;

class WordApiCest
{
    public function isWordListValid(ApiTester $I)
    {
        $I->wantToTest("Receive all the words in the database test.");
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('/words');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     * @param Example $word
     * @example {"word" : "computer"}
     */
    public function tryToSubmitWord(ApiTester $I, Example $word)
    {
        $I->wantToTest(sprintf("Submit word [%s] into the database test.", $word['word']));
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendGet('/words', [ 'word' => $word['word'] ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     * @param Example $word
     * @example {"id" : 3}
     */
    public function tryToReceiveWord(ApiTester $I, Example $word)
    {
        $I->wantToTest(sprintf("Receive word with id [%s] from database test.", $word['id']));
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendGet('/words/' . $word['id']);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIsSuccessful();
    }

    /**
     * @param ApiTester $I
     * @param Example $word
     * @example {"id" : "3", "word" : "forever", "hyphenated_word" : "fo-re-ver"}
     */
    public function tryToUpdateWord(ApiTester $I, Example $word)
    {
        $I->wantToTest(sprintf("Update word with id [%s] from database test.", $word['id']));
        $I->sendPutAsJson('/words/' . $word['id'],
            ['word' => $word['word'],
             'hyphenated_word' => $word['hyphenated_word']
            ]
        );
        $I->seeResponseIsJson();
        $I->seeResponseCodeIsSuccessful();
    }

    /**
     * @param ApiTester $I
     * @param Example   $word
     * @example {"id" : 3}
     */
    public function tryToDeleteWord(ApiTester $I, Example $word)
    {
        $I->wantToTest(sprintf("Delete word with id [%s] from database test.", $word['id']));
        $I->sendDelete('/words/' . $word['id']);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIsSuccessful();
    }
}
