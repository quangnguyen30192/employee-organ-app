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
 * Implementation of EmployeeTreeBuilderBase to build up an employee tree which has json format conforming
 * to employee chart at the view: OrganChart (https://github.com/dabeng/OrgChart)
 */
class EmployeeJsonChartBuilder extends EmployeeTreeBuilderBase {

    /**
     * EmployeeJsonChartBuilder constructor.
     *
     * @param EmployeeTreeService $employeeTreeService
     */
    public function __construct(EmployeeTreeService $employeeTreeService) {
        parent::__construct($employeeTreeService);
    }

    protected function createEmployeeNode(string $employeeName): EmployeeNode {
        return new EmployeeChartNode($employeeName);
    }
}