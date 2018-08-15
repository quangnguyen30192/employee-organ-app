<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/13/18
 * Time: 10:42 PM
 */

namespace Tests\Unit;

use App\DataProviders\EmployeeJsonDataProvider;
use App\EmployeeSupervisorDto;
use PHPUnit\Framework\TestCase;

class EmployeeJsonDataProviderTest extends TestCase {

    private $employeeDataProvider;

    public function setUp() {
        $this->employeeDataProvider = new EmployeeJsonDataProvider();
    }

    public function testParseJson() {
        $testString = '{ "Pete": "Nick", "Barbara": "Nick", "Nick": "Sophie", "Sophie": "Jonas" }';

        $actual = $this->employeeDataProvider->parseEmployeeData($testString);

        $this->assertSame(count($actual), 4);

        $expected = [
            new EmployeeSupervisorDto("Pete", "Nick"),
            new EmployeeSupervisorDto("Barbara", "Nick"),
            new EmployeeSupervisorDto("Nick", "Sophie"),
            new EmployeeSupervisorDto("Sophie", "Jonas")
        ];

        $this->assertSame(array_diff($expected, $actual), []);
    }

    public function testParseJsonShouldOnlyHaveValueAsString() {
        $testString = '{ "Pete": "Nick", "Barbara": 1, "Nick": "Sophie", "Sophie": "Jonas" }';

        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Value: 1 is not a string - for Key: Barbara');
        $this->employeeDataProvider->parseEmployeeData($testString);
    }

    public function testParseJsonShouldNotPassByArray() {
        $testString = '{ "Pete": "Nick", "Barbara": 1, "Nick": "Sophie", "Sophie": "Jonas" }';

        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Json string input is not valid');

        $array = json_decode($testString, true);
        $this->employeeDataProvider->parseEmployeeData($array);
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
            new EmployeeSupervisorDto("База данни грешка", "База данни грешка test")
        ];

        $this->assertSame(array_diff($expected, $actual), []);
    }

    public function testJsonShouldNotHaveDuplicateEmployeeName() {
        $testString = '{ "Pete": "Nick", "Pete": "Jame", "Nick": "Sophie", "Sophie": "Jonas" }';

        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('Duplicate key \'Pete\' at line 1');
        $this->employeeDataProvider->parseEmployeeData($testString);
    }

}