<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/17/18
 * Time: 12:10 PM
 */

namespace App\Models\Chart;


use App\Models\EmployeeNode;
use App\Models\EmployeeTree;
use App\Services\EmployeeTreeService;

/**
 * Class representing for Employee Organization Chart responsible for building tree for organization chart form
 */
class EmployeeChart extends EmployeeTree {

    /**
     * EmployeeChart constructor.
     *
     * @param $employeeTreeService
     * @param $employeeRootNode root node of the employee tree
     */
    public function __construct(EmployeeNode $employeeRootNode, EmployeeTreeService $employeeTreeService) {
        parent::__construct($employeeRootNode, $employeeTreeService);
    }

    protected function newEmployeeNode(string $employeeName): EmployeeNode {
        return new EmployeeChartNode($employeeName);
    }
}