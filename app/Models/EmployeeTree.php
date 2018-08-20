<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/17/18
 * Time: 12:10 PM
 */

namespace App\Models;

use App\Services\EmployeeTreeService;
use JsonSerializable;

/**
 * Class representing for Employee Tree which contains the root node and responsible for building tree from the root node
 */
class EmployeeTree implements JsonSerializable {

    protected $employeeRootNode;
    protected $employeeTreeService;

    /**
     * EmployeeTree constructor.
     *
     * @param $employeeTreeService implementation of EmployeeTreeService
     * @param $employeeRootNode employee root node of the employee tree
     */
    public function __construct($employeeRootNode, EmployeeTreeService $employeeTreeService) {
        $this->employeeRootNode = $employeeRootNode;
        $this->employeeTreeService = $employeeTreeService;
    }

    /**
     * Build a employee hierarchy tree based on the employee root node.
     *
     * @param $employeeDtos array of EmployeeDtos
     */
    public function buildTreeOnRootNode($employeeDtos) {
        $this->buildTree($this->employeeRootNode, $employeeDtos);
    }

    /**
     * Build a employee hierarchy tree based on the input employee node by recursively
     * expanding the subordinates under that node.
     *
     * The input employee node would have full hierarchy after processed
     *
     * @param $employeeNode employee node that should exist in the employee hierarchy tree
     * @param $employeeDtos array of EmployeeDtos
     */
    protected function buildTree($employeeNode, $employeeDtos) {
        $subordinates = $this->employeeTreeService->findEmployeesUnderSupervisor($employeeNode->getEmployeeName(), $employeeDtos);

        foreach ($subordinates as $subordinate) {
            $subEmpNode = $this->newEmployeeNode($subordinate);
            $employeeNode->addSubordinate($subEmpNode);
            $this->buildTree($subEmpNode, $employeeDtos);
        }
    }

    protected function newEmployeeNode($employeeName) {
        return new EmployeeNode($employeeName);
    }

    /**
     * Specify data which should be serialized to JSON
     */
    public function jsonSerialize() {
        return $this->employeeRootNode->jsonSerialize();
    }
}