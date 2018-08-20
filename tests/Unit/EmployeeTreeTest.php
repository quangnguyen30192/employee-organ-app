<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/17/18
 * Time: 2:50 PM
 */

namespace Tests\Unit;


use App\DataProviders\EmployeeJsonDataProvider;
use App\Models\EmployeeNode;
use App\Models\EmployeeTree;
use App\Services\EmployeeTreeServiceImpl;
use Tests\TestCase;

class EmployeeTreeTest extends TestCase {

    private $employeeDataProvider;
    private $employeeTreeService;

    public function setUp() {
        $this->employeeDataProvider = new EmployeeJsonDataProvider();
        $this->employeeTreeService = new EmployeeTreeServiceImpl();
    }

    public function testBuildTree() {
        $testString = '{ "Pete": "Nick", "Barbara": "Nick", "Nick": "Sophie", "Sophie": "Jonas" }';
        $employeeData = $this->employeeDataProvider->parseEmployeeData($testString);

        $boss = $this->employeeTreeService->findBoss($employeeData);
        $employeeTree = new EmployeeTree(new EmployeeNode($boss), $this->employeeTreeService);
        $employeeTree->buildTreeOnRootNode($employeeData);

        $this->assertSame(json_encode($employeeTree), '{"Jonas":[{"Sophie":[{"Nick":[{"Pete":[]},{"Barbara":[]}]}]}]}');
    }

}