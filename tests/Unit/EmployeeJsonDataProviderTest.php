<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/13/18
 * Time: 10:42 PM
 */

namespace Tests\Unit;

use App\Models\EmployeeDto;
use App\Services\EmployeeDataProvider;
use PHPUnit\Framework\TestCase;

class EmployeeJsonDataProviderTest extends TestCase {

    private $employeeDataProvider;

    public function setUp() {
        $this->employeeDataProvider = new EmployeeDataProvider();
    }

    public function testParseJson() {
        $testString = '{ "Pete": "Nick", "Barbara": "Nick", "Nick": "Sophie", "Sophie": "Jonas" }';

        $actual = $this->employeeDataProvider->parseEmployeeData($testString);

        $expected = [
            new EmployeeDto("Pete", "Nick"),
            new EmployeeDto("Barbara", "Nick"),
            new EmployeeDto("Nick", "Sophie"),
            new EmployeeDto("Sophie", "Jonas")
        ];

        $this->assertEquals($expected, $actual);
    }

    public function testParseJsonShouldOnlyHaveValueAsString() {
        $testString = '{ "Pete": "Nick", "Barbara": 1, "Nick": "Sophie", "Sophie": "Jonas" }';

        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Value is not a string - for Key: Barbara');
        $this->employeeDataProvider->parseEmployeeData($testString);
    }

    public function testParseJsonShouldOnlyHaveValueAsStringTestWithEmptyKey() {
        $testString = '{ "Pete": "Nick", "  ": 1, "Nick": "Sophie", "Sophie": "Jonas" }';

        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Json data contains empty key where has the value: 1');
        $this->employeeDataProvider->parseEmployeeData($testString);
    }

    public function testParseJsonCanHaveNullValue() {
        $testString = '{ "Pete": "Nick", "Barbara": null, "Nick": "Sophie", "Sophie": "Jonas" }';

        $actual = $this->employeeDataProvider->parseEmployeeData($testString);

        $expected = [
            new EmployeeDto("Pete", "Nick"),
            new EmployeeDto("Barbara", ""),
            new EmployeeDto("Nick", "Sophie"),
            new EmployeeDto("Sophie", "Jonas")
        ];

        $this->assertEquals($expected, $actual);
    }

    public function testParseJsonCanHaveEmptyValue() {
        $testString = '{ "Pete": "Nick", "Barbara": "  ", "Nick": "Sophie", "Sophie": "Jonas" }';

        $actual = $this->employeeDataProvider->parseEmployeeData($testString);

        $expected = [
            new EmployeeDto("Pete", "Nick"),
            new EmployeeDto("Barbara", ""),
            new EmployeeDto("Nick", "Sophie"),
            new EmployeeDto("Sophie", "Jonas")
        ];

        $this->assertEquals($expected, $actual);
    }

    public function testParseJsonCanHaveEmptyValueAndTestingTrim() {
        $testString = '{ "Pete   ": " Nick " , "  Barbara  ": "  ", "   Nick": "Sophie", "Sophie  ": "" }';

        $actual = $this->employeeDataProvider->parseEmployeeData($testString);

        $expected = [
            new EmployeeDto("Pete", "Nick"),
            new EmployeeDto("Barbara", ""),
            new EmployeeDto("Nick", "Sophie"),
            new EmployeeDto("Sophie", "")
        ];

        $this->assertEquals($expected, $actual);
    }

    public function testParseJsonCanHaveNullEmptyValueAndTestingTrim() {
        $testString = '{ "Pete   ": " Nick " , "  Barbara  ": null, "   Nick": "Sophie", "Sophie  ": "" }';

        $actual = $this->employeeDataProvider->parseEmployeeData($testString);

        $expected = [
            new EmployeeDto("Pete", "Nick"),
            new EmployeeDto("Barbara", ""),
            new EmployeeDto("Nick", "Sophie"),
            new EmployeeDto("Sophie", "")
        ];

        $this->assertEquals($expected, $actual);
    }

    public function testJsonShouldNotHaveEmptyKey() {
        $testString = '{ "": "Jim" }';
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Json data contains empty key where has the value: Jim');
        $this->employeeDataProvider->parseEmployeeData($testString);
    }

    public function testJsonShouldNotHaveEmptyKeyTrimValue() {
        $testString = '{ "": "    Jim    " }';
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Json data contains empty key where has the value: Jim');
        $this->employeeDataProvider->parseEmployeeData($testString);
    }

    public function testJsonShouldNotHaveEmptyKeyAndValue() {
        $testString = '{ "": "" }';
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Json data contains empty key where also has empty value');
        $this->employeeDataProvider->parseEmployeeData($testString);
    }

    public function testJsonShouldNotHaveEmptyKeyAndNullValue() {
        $testString = '{ "": null }';
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Json data contains empty key where also has empty value');
        $this->employeeDataProvider->parseEmployeeData($testString);
    }

    public function testJsonShouldNotHaveNullKey() {
        $testString = '{ null: "Jim" }';
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Json string input is not valid');
        $this->employeeDataProvider->parseEmployeeData($testString);
    }

    public function testJsonShouldNotHaveNullKeyNullValue() {
        $testString = '{ null: null }';
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Json string input is not valid');
        $this->employeeDataProvider->parseEmployeeData($testString);
    }

    public function testParseJsonShouldNotHaveKeyAsString() {
        $testString = '{ "Pete": "Nick", Barbara: 1, "Nick": "Sophie", "Sophie": "Jonas" }';

        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Json string input is not valid');
        $this->employeeDataProvider->parseEmployeeData($testString);
    }

    public function testParseJsonShouldNotHaveNestedValueArray() {
        $testString = '{ "Pete": "Nick", "Barbara": ["Tina": "Tim"], "Nick": "Sophie", "Sophie": "Jonas" }';

        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Json string input is not valid');
        $this->employeeDataProvider->parseEmployeeData($testString);
    }

    public function testParseJsonShouldNotHaveNestedJson() {
        $testString = '{ "Pete": "Nick", "Barbara": {"Tina": "Tim"}, "Nick": "Sophie", "Sophie": "Jonas" }';

        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Json file content should not contain nested multi-dimensional json');
        $this->employeeDataProvider->parseEmployeeData($testString);
    }

    public function testParseJsonWithUtf8() {
        $testString = '{ "База данни грешка": "База данни грешка test"}';
        $actual = $this->employeeDataProvider->parseEmployeeData($testString);

        $expected = [
            new EmployeeDto("База данни грешка", "База данни грешка test")
        ];

        $this->assertEquals($expected, $actual);
    }

    public function testJsonShouldNotHaveDuplicateEmployeeName() {
        $testString = '{ "Pete": "Nick", "Pete": "Jame", "Nick": "Sophie", "Sophie": "Jonas" }';

        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Duplicate employee Pete at line 1');
        $this->employeeDataProvider->parseEmployeeData($testString);
    }

    public function testEmptyJson() {
        $testString = '{}';

        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Json string input is not valid');
        $this->employeeDataProvider->parseEmployeeData($testString);
    }

    public function testEmptyStringRequest() {
        $testString = '';

        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Json string input is not valid');
        $this->employeeDataProvider->parseEmployeeData($testString);
    }

    public function testJsonShouldNotHaveTheSameEmployeeSupervisor() {
        $testString = '{ "Pete": "Pete", "Nick": "Sophie", "Sophie": "Jonas" }';

        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Employee and supervisor have the same name: \'Pete\'');
        $this->employeeDataProvider->parseEmployeeData($testString);
    }
}