<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/13/18
 * Time: 10:42 PM
 */

namespace Tests\Unit;

use App\DataProviders\EmployeeJsonDataProvider;
use App\Services\EmployeeTreeServiceImpl;
use PHPUnit\Framework\TestCase;

class EmployeeTreeServiceImplTest extends TestCase {

    private $employeeDataProvider;
    private $employeeTreeService;

    public function setUp() {
        $this->employeeDataProvider = new EmployeeJsonDataProvider();
        $this->employeeTreeService = new EmployeeTreeServiceImpl();
    }

    public function testFindBoss() {
        $testString = '{ "Pete": "Nick", "Barbara": "Nick", "Nick": "Sophie", "Sophie": "Jonas" }';
        $employeeData = $this->employeeDataProvider->parseEmployeeData($testString);

        $actual = $this->employeeTreeService->findBoss($employeeData);
        $this->assertSame($actual, "Jonas");
    }

    public function testFindBossWith2EmployeesHasSameTopBoss() {
        $testString = '{ "Pete": "Nick", "Barbara": "Nick" }';
        $employeeData = $this->employeeDataProvider->parseEmployeeData($testString);

        $actual = $this->employeeTreeService->findBoss($employeeData);

        $this->assertSame($actual, "Nick");
    }

    public function testFindBossWith2DifferentBosses() {
        $testString = '{ "Pete": "Nick", "Jame": "Nick", "Tomas": "Sophie", "Nick": "Jonas" }';
        $employeeData = $this->employeeDataProvider->parseEmployeeData($testString);

        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('There is more than one top boss: Sophie, Jonas');
        $this->employeeTreeService->findBoss($employeeData);
    }

    public function testFindBossWithNonsenseHierarchy() {
        $testString = '{ "Pete": "Nick", "Tim": "Jame", "Tina": "Mina" }';
        $employeeData = $this->employeeDataProvider->parseEmployeeData($testString);

        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('There is more than one top boss: Nick, Jame, Mina');
        $this->employeeTreeService->findBoss($employeeData);
    }

    public function testFindBossWithEmptyValue() {
        $testString = '{ "Pete": "", "Barbara": "Tina" }';
        $employeeData = $this->employeeDataProvider->parseEmployeeData($testString);

        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('There is more than one top boss: Pete, Tina');
        $this->employeeTreeService->findBoss($employeeData);
    }

    public function testFindBossHavingManySubordinates() {
        $testString = '{ "Pete": "Tina", "Barbara": "Tina", "Minh" : "Tina", "Sang": "Tina" }';
        $employeeData = $this->employeeDataProvider->parseEmployeeData($testString);

        $actual = $this->employeeTreeService->findBoss($employeeData);
        $this->assertSame($actual, "Tina");
    }
}