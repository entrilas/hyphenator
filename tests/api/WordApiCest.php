<?php

use Codeception\Example;

class WordApiCest
{
    /**
     * @param ApiTester $I
     * @param Example $word
     * @example {"word" : "computer"}
     */
    public function tryToSubmitWord(ApiTester $I, Example $word)
    {
        $I->wantToTest(sprintf("Submit word [%s] into the database test.", $word['word']));
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPostAsJson('/words', [ 'word' => $word['word'] ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();

        $I->sendGet('/words');
        $I->seeResponseContainsJson(['data' => [
            'word' => $word['word']
        ]]);
    }

    /**
     * @param ApiTester $I
     * @param Example   $word
     * @example {"word" : "computer"}
     */
    public function tryToReceiveAllWords(ApiTester $I, Example $word)
    {
        $I->wantToTest("Receive all the words in the database test.");
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('/words');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();

        $I->seeResponseContainsJson(['data' => [
            'word' => $word['word']
        ]]);
    }

    /**
     * @param ApiTester $I
     * @param Example $word
     * @example {"id" : "1", "word" : "computer", "hyphenated_word" : "com-put-er"}
     */
    public function tryToReceiveWord(ApiTester $I, Example $word)
    {
        $I->wantToTest(sprintf("Receive word with id [%s] from database test.", $word['id']));
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendGet('/words/' . $word['id']);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIsSuccessful();

        $I->sendGet('/words');
        $I->seeResponseContainsJson(['data' => [
            'word' => $word['word']
        ]]);
    }

    /**
     * @param ApiTester $I
     * @param Example $word
     * @example {"id" : "1", "word" : "forever", "hyphenated_word" : "for-ev-er"}
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

        $I->sendGet('/words');
        $I->seeResponseContainsJson(['data' => [
            'word' => $word['word']
        ]]);
    }

    /**
     * @param ApiTester $I
     * @param Example   $word
     * @example {"id" : "1", "word" : "forever", "hyphenated_word" : "for-ev-er"}
     */
    public function tryToDeleteWord(ApiTester $I, Example $word)
    {
        $I->wantToTest(sprintf("Delete word with id [%s] from database test.", $word['id']));
        $I->sendDelete('/words/' . $word['id']);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIsSuccessful();

        $I->sendGET('/words');
        $I->dontSeeResponseContainsJson(['data' => [
            'word' => $word['word']
        ]]);
    }
}
