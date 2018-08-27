<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/17/18
 * Time: 2:50 PM
 */

namespace Tests\Unit;


use App\Services\EmployeeDataProvider;
use App\Services\EmployeeTreeService;
use App\TreeBuilders\EmployeeJsonChartBuilder;
use App\TreeBuilders\EmployeeTreeBuilder;
use Tests\TestCase;

class EmployeeTreeBuilderTest extends TestCase {

    private $employeeDataProvider;
    private $employeeTreeService;

    public function setUp() {
        $this->employeeDataProvider = new EmployeeDataProvider();
        $this->employeeTreeService = new EmployeeTreeService();
    }

    public function testBuildTree() {
        $testString = '{ "Pete": "Nick", "Barbara": "Nick", "Nick": "Sophie", "Sophie": "Jonas" }';
        $employeeData = $this->employeeDataProvider->parseEmployeeData($testString);

        $employeeTreeBuilder = new EmployeeTreeBuilder($this->employeeTreeService);
        $employeeTree = $employeeTreeBuilder->buildTree($employeeData);

        $this->assertSame(json_encode($employeeTree), '{"Jonas":[{"Sophie":[{"Nick":[{"Pete":[]},{"Barbara":[]}]}]}]}');
    }

    public function testBuildChart() {
        $testString = '{ "Pete": "Nick", "Barbara": "Nick", "Nick": "Sophie", "Sophie": "Jonas" }';
        $employeeData = $this->employeeDataProvider->parseEmployeeData($testString);

        $employeeTreeBuilder = new EmployeeJsonChartBuilder($this->employeeTreeService);
        $employeeTree = $employeeTreeBuilder->buildTree($employeeData);

        $this->assertSame(json_encode($employeeTree), '{"name":"Jonas","children":[{"name":"Sophie","children":[{"name":"Nick","children":[{"name":"Pete","children":[]},{"name":"Barbara","children":[]}]}]}]}');
    }
}