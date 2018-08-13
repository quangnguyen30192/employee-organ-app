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

}