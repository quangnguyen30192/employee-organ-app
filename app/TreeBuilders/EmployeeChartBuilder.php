<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/17/18
 * Time: 12:10 PM
 */

namespace App\TreeBuilders;

use App\Models\EmployeeChartNode;
use App\Models\EmployeeNode;
use App\Services\EmployeeTreeService;

/**
 * Class that has responsible for building up an employee chart
 */
class EmployeeChartBuilder extends EmployeeTreeBuilder {

    /**
     * EmployeeChartBuilder constructor.
     *
     * @param $employeeTreeService
     */
    public function __construct(EmployeeTreeService $employeeTreeService) {
        parent::__construct($employeeTreeService);
    }

    protected function newEmployeeNode(string $employeeName): EmployeeNode {
        return new EmployeeChartNode($employeeName);
    }
}