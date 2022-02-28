<?php

use Codeception\Example;

class PatternApiCest
{
    private function createPatternDataProvider(): array
    {
        return [
            ['pattern' => '.ba4mf5s'],
            ['pattern' => '.t3st4d'],
        ];
    }
    /**
     * @param ApiTester $I
     * @param Example $pattern
     * @dataProvider createPatternDataProvider
     */
    public function tryToSubmitPattern(ApiTester $I, Example $pattern)
    {
        $I->wantToTest(sprintf("Submit pattern [%s] into the database test.", $pattern['pattern']));
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPostAsJson('/patterns', [ 'pattern' => $pattern['pattern'] ]);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();

        $I->sendGet('/patterns');
        $I->seeResponseContainsJson(['data' => [
            'pattern' => $pattern['pattern']
        ]]);
    }

    /**
     * @param ApiTester $I
     * @param Example   $pattern
     * @dataProvider createPatternDataProvider
     */
    public function tryToReceiveAllPatterns(ApiTester $I, Example $pattern)
    {
        $I->wantToTest("Receive all the patterns in the database test.");
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGet('/patterns');
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseIsJson();

        $I->seeResponseContainsJson(['data' => [
            'pattern' => $pattern['pattern']
        ]]);
    }

    /**
     * @param ApiTester $I
     * @param Example $pattern
     * @dataProvider createPatternDataProvider
     */
    public function tryToReceivePattern(ApiTester $I, Example $pattern)
    {
        $I->sendGet('/patterns');
        $id = $I->grabDataFromResponseByJsonPath(
            sprintf("$.data[?(@.pattern == '%s')].id",
            $pattern['pattern']))[0];

        $I->wantToTest(sprintf("Receive pattern with id [%s] from database test.", $id));
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendGet('/patterns/' . $id);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson(['data' => [
            'pattern' => $pattern['pattern']
        ]]);
    }

    /**
     * @param ApiTester $I
     * @param Example $pattern
     * @dataProvider createPatternDataProvider
     */
    public function tryToUpdatePattern(ApiTester $I, Example $pattern)
    {
        $I->sendGet('/patterns');
        $id = $I->grabDataFromResponseByJsonPath(
            sprintf("$.data[?(@.pattern == '%s')].id",
                $pattern['pattern']))[0];

        $I->wantToTest(sprintf("Update pattern with id [%s] from database test.", $id));
        $I->sendPutAsJson('/patterns/' . $id,
            ['pattern' => $pattern['pattern']]
        );
        $I->seeResponseIsJson();
        $I->seeResponseCodeIsSuccessful();

        $I->sendGet('/patterns');
        $I->seeResponseContainsJson(['data' => [
            'pattern' => $pattern['pattern']
        ]]);
    }

    /**
     * @param ApiTester $I
     * @param Example   $pattern
     * @dataProvider createPatternDataProvider
     */
    public function tryToDeletePattern(ApiTester $I, Example $pattern)
    {
        $I->sendGet('/patterns');
        $id = $I->grabDataFromResponseByJsonPath(
            sprintf("$.data[?(@.pattern == '%s')].id",
                $pattern['pattern']))[0];

        $I->wantToTest(sprintf("Delete pattern with id [%s] from database test.", $id));
        $I->sendDelete('/patterns/' . $id);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIsSuccessful();

        $I->sendGET('/patterns');
        $I->dontSeeResponseContainsJson(['data' => [
            'pattern' => $pattern['pattern']
        ]]);
    }
}
