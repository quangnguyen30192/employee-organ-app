<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/13/18
 * Time: 2:33 PM
 */

namespace App\Services;

use App\EmployeeNode;

/**
 * Default implementation for EmployeeTreeService
 */
class EmployeeTreeServiceImpl implements EmployeeTreeService {

    public function __construct() {

    }

    public function findBoss($employeeData) {
        $employees = collect($employeeData)->map(function ($data) {
            return $data->getEmployee();
        })->toArray();

        $topBosses = collect($employeeData)->filter(function ($data) use ($employees) {
            $supervisor = $data->getSupervisor();

            return !in_array($supervisor, $employees, true);
        })->first();

        if ($topBosses->getSupervisor() && trim($topBosses->getSupervisor()) !== "") {
            return $topBosses->getSupervisor();
        }

        return $topBosses->getEmployee();
    }

    public function findEmployeesUnderSupervisor($supervisor, $employeeData): array {
        return collect($employeeData)->filter(function ($data) use ($supervisor) {
            return $data->getSupervisor() === $supervisor;
        })->map(function ($data) {
            return $data->getEmployee();
        })->toArray();
    }

    public function buildTree($employeeNode, $employeeData) {
        $subordinates = $this->findEmployeesUnderSupervisor($employeeNode->getEmployeeName(), $employeeData);

        foreach ($subordinates as $subordinate) {
            $subEmpNode = new EmployeeNode($subordinate);
            $employeeNode->addSubordinate($subEmpNode);
            $this->buildTree($subEmpNode, $employeeData);
        }

        return $employeeNode;
    }
}