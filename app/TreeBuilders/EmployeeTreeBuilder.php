<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/17/18
 * Time: 12:10 PM
 */

namespace App\TreeBuilders;

use App\Models\EmployeeNode;
use App\Services\EmployeeTreeService;

/**
 * Implementation of EmployeeTreeBuilderBase to build up an employee tree which has json format to show up to the user
 * (as the main requirement)
 */
class EmployeeTreeBuilder extends EmployeeTreeBuilderBase {

    /**
     * EmployeeTreeBuilder constructor.
     *
     * @param EmployeeTreeService $employeeTreeService
     */
    public function __construct(EmployeeTreeService $employeeTreeService) {
        parent::__construct($employeeTreeService);
    }

    protected function createEmployeeNode(string $employeeName): EmployeeNode {
        return new EmployeeNode($employeeName);
    }
}