<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/12/18
 * Time: 4:07 PM
 */

namespace Tests\Unit;


use App\Helpers\CommonUtils;
use Tests\TestCase;


/**
 * Class that provides unit tests for JsonParser class
 */
class CommonUtilsTest extends TestCase {

    public function testIsValidJsonWithNonsenseValues() {
        $values = [
            false,
            true,
            null,
            'abc',
            '23',
            23,
            '23.5',
            23.5,
            '',
            ' ',
            '0',
            0
        ];
        foreach ($values as $value) {
            $this->assertFalse(CommonUtils::isValidJson($value));
        }
    }

    public function testIsValidJson() {
        $testString = '{ "Pete": "Nick", "Barbara": "Nick", "Nick": "Sophie", "Sophie": "Jonas" }';
        $actual = CommonUtils::isValidJson($testString);

        $expected = [
            "Pete" => "Nick",
            "Barbara" => "Nick",
            "Nick" => "Sophie",
            "Sophie" => "Jonas",
        ];

        $this->assertSame(array_diff($expected, $actual), []);
    }

    public function testIsValidJsonEmpty() {
        $testString = '{}';
        $actual = CommonUtils::isValidJson($testString);

        $this->assertSame(json_encode([]), json_encode($actual));
    }

    public function testIsValidJsonShouldNotHaveInvalidSyntaxNoQuotesForKey() {
        $testString = '{ "Pete": "Nick", Barbara: "Nick", "Nick": "Sophie", "Sophie": "Jonas" }';
        $isValidJson = CommonUtils::isValidJson($testString);

        $this->assertSame(json_last_error(), 4);
        $this->assertSame(json_last_error_msg(), 'Syntax error');
        $this->assertFalse($isValidJson);
    }


    public function testIsValidJsonShouldNotHaveInvalidSyntaxCaseNoQuotesForValue() {
        $testString = '{ "Pete": "Nick", "Barbara": Nick, "Nick": "Sophie", "Sophie": "Jonas" }';
        $isValidJson = CommonUtils::isValidJson($testString);

        $this->assertSame(json_last_error(), 4);
        $this->assertSame(json_last_error_msg(), 'Syntax error');
        $this->assertFalse($isValidJson);
    }

    public function testIsValidJsonShouldNotHaveInvalidSyntaxCaseNoQuotesForKeyValue() {
        $testString = '{ "Pete": "Nick", Barbara: Nick, "Nick": "Sophie", "Sophie": "Jonas" }';
        $isValidJson = CommonUtils::isValidJson($testString);

        $this->assertSame(json_last_error(), 4);
        $this->assertSame(json_last_error_msg(), 'Syntax error');
        $this->assertFalse($isValidJson);
    }

    public function testIsValidJsonWithNullValue() {
        $testString = '{ "Pete": "Nick", "Barbara": null}';
        $actual = CommonUtils::isValidJson($testString);

        $expected = [
            "Pete" => "Nick",
            "Barbara" => null
        ];

        $this->assertSame(array_diff($expected, $actual), []);
    }

    public function testIsValidJsonWithUtf8() {
        $testString = '{ "База данни грешка": "База данни грешка test"}';
        $actual = CommonUtils::isValidJson($testString);

        $expected = [
            "База данни грешка" => "База данни грешка test"
        ];

        $this->assertSame(array_diff($expected, $actual), []);
    }
}