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
 * Class that has responsible for building up an employee tree
 */
class EmployeeTreeBuilder {

    private $employeeTreeService;

    /**
     * EmployeeTreeBuilder constructor.
     *
     * @param EmployeeTreeService $employeeTreeService
     */
    public function __construct(EmployeeTreeService $employeeTreeService) {
        $this->employeeTreeService = $employeeTreeService;
    }

    /**
     * Build up an employee tree from employee dtos
     *
     * @param array $employeeDtos
     *
     * @return EmployeeNode that represents as an employee tree
     */
    public function buildTree(array $employeeDtos): EmployeeNode {
        $boss = $this->employeeTreeService->findBoss($employeeDtos);

        $employeeRootNode = $this->newEmployeeNode($boss);
        $this->buildTreeFromEmployeeNode($employeeRootNode, $employeeDtos);

        return $employeeRootNode;
    }

    /**
     * Build up an employee hierarchy tree based on the input employee node by recursively
     * expanding the subordinates under that node.
     *
     * The input employee node would become a tree that has full hierarchy after processed
     *
     * @param $employeeNode
     * @param $employeeDtos
     */
    private function buildTreeFromEmployeeNode(EmployeeNode $employeeNode, array $employeeDtos): void {
        $subordinates = $this->employeeTreeService->findEmployeesUnderSupervisor($employeeNode->getEmployeeName(), $employeeDtos);

        foreach ($subordinates as $subordinate) {
            $subEmpNode = $this->newEmployeeNode($subordinate);
            $employeeNode->addSubordinate($subEmpNode);
            $this->buildTreeFromEmployeeNode($subEmpNode, $employeeDtos);
        }
    }

    protected function newEmployeeNode(string $employeeName): EmployeeNode {
        return new EmployeeNode($employeeName);
    }
}