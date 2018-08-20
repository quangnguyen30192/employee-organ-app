<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/17/18
 * Time: 12:10 PM
 */

namespace App\Models;


use App\Services\EmployeeTreeService;

/**
 * Class representing for Employee Organization Chart responsible for building tree for organization chart form
 */
class EmployeeChart extends EmployeeTree {

    /**
     * EmployeeChart constructor.
     *
     * @param $employeeTreeService implementation of EmployeeTreeService
     * @param $employeeRootNode employee root node of the employee tree
     */
    public function __construct($employeeRootNode, EmployeeTreeService $employeeTreeService) {
        parent::__construct($employeeRootNode, $employeeTreeService);
    }

    protected function newEmployeeNode($employeeName) {
        return new EmployeeChartNode($employeeName);
    }
}