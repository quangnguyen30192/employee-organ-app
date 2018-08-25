<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/25/18
 * Time: 8:17 PM
 */

namespace App\TreeBuilders;


use App\Models\EmployeeNode;
use App\Services\EmployeeTreeService;

/**
 * Class that has responsible for building up an employee tree
 *
 * The employee tree would have different json format base on its node - EmployeeNode, which has to implement JsonSerializer
 * to define the proper json output
 */
abstract class EmployeeTreeBuilderBase {

    private $employeeTreeService;

    /**
     * EmployeeTreeBuilderBase constructor.
     *
     * Since we have EmployeeTreeBuilderFactory, the constructor of this base class and its subclasses should have
     * protected access modifier. But they will have public access modifier just only for unit testing purpose.
     *
     * The subclasses of this base class should not be instantiated directly from their constructors in the application
     * (except unit testing), they should be from EmployeeTreeBuilderFactory::createEmployeeNode()
     *
     * @param EmployeeTreeService $employeeTreeService
     */
    protected function __construct(EmployeeTreeService $employeeTreeService) {
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

        $employeeRootNode = $this->createEmployeeNode($boss);
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
            $subEmpNode = $this->createEmployeeNode($subordinate);
            $employeeNode->addSubordinate($subEmpNode);
            $this->buildTreeFromEmployeeNode($subEmpNode, $employeeDtos);
        }
    }

    /**
     * Create an employee node
     *
     * Lets subclasses decide which employee node should be used to define proper json format for employee tree
     *
     * @param $employeeName
     *
     * @return EmployeeNode
     */
    protected abstract function createEmployeeNode(string $employeeName): EmployeeNode;
}