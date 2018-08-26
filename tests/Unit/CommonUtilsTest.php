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

    public function testIsValidJsonWithInvalidValues() {
        $values = ['abc', '23', '23.5', '', ' ', '0'];
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

        $this->assertEquals($expected, $actual);
    }

    public function testIsValidJsonEmpty() {
        $testString = '{}';
        $actual = CommonUtils::isValidJson($testString);

        $this->assertEquals([], $actual);
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

        $this->assertEquals($expected, $actual);
    }

    public function testIsValidJsonWithNullKey() {
        $testString = '{ null : "Nick" }';
        $actual = CommonUtils::isValidJson($testString);
        $this->assertFalse($actual);
    }

    public function testIsValidJsonWithUtf8() {
        $testString = '{ "База данни грешка": "База данни грешка test"}';
        $actual = CommonUtils::isValidJson($testString);

        $expected = [
            "База данни грешка" => "База данни грешка test"
        ];

        $this->assertEquals($expected, $actual);
    }

    public function testHasIdenticalElements() {
        $actual = CommonUtils::hasIdenticalElements(['a', 'a', 'a', 'a']);
        $this->assertTrue($actual);
    }

    public function testHasIdenticalElementsNull() {
        $actual = CommonUtils::hasIdenticalElements(['a', null, 'a', 'a']);
        $this->assertFalse($actual);
    }

    public function testHasIdenticalElementWithEmptyArrayInput() {
        $actual = CommonUtils::hasIdenticalElements([]);
        $this->assertFalse($actual);
    }

    public function testIsEmptyOrBlankTestEmpty() {
        $actual = CommonUtils::isEmptyOrBlank('');
        $this->assertTrue($actual);
    }

    public function testIsEmptyOrBlankTestBlank() {
        $actual = CommonUtils::isEmptyOrBlank('   ');
        $this->assertTrue($actual);
    }

    public function testisEmptyOrBlankIsEmpty() {
        $actual = CommonUtils::isEmptyOrBlank('Hello World');
        $this->assertFalse($actual);
    }

    public function testIsEmptyOrBlankTestNotTrimString() {
        $actual = CommonUtils::isEmptyOrBlank('  Hello World         ');
        $this->assertFalse($actual);
    }

    public function testTrimStringOrValueTestString() {
        $actual = CommonUtils::trimStringOrValue('            Hello World               ');
        $this->assertSame('Hello World', $actual);
    }

    public function testTrimStringOrValueTestNotString() {
        $actual = CommonUtils::trimStringOrValue(1123);
        $this->assertSame(1123, $actual);

    }

    public function testTrimStringOrValueTestNull() {
        $actual = CommonUtils::trimStringOrValue(null);
        $this->assertNull($actual);
    }
}