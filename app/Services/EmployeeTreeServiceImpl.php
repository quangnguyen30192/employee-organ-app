<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/13/18
 * Time: 2:33 PM
 */

namespace App\Services;

use App\EmployeeNode;
use App\Helpers\CommonUtils;
use InvalidArgumentException;

/**
 * Default implementation for EmployeeTreeService
 */
class EmployeeTreeServiceImpl implements EmployeeTreeService {

    public function __construct() {

    }

    public function findBoss($employeeDtos) {
        if ($employeeDtos === null || count($employeeDtos) == 0) {
            throw new InvalidArgumentException("There is no employee dtos provided");
        }

        if (count($employeeDtos) == 1) {
            return $this->getValidBossName($employeeDtos[0]);
        }

        $employees = collect($employeeDtos)->map(function ($employeeDto) {
            return $employeeDto->getEmployee();
        })->toArray();

        $bossDtos = collect($employeeDtos)->filter(function ($employeeDto) use ($employees) {
            $supervisor = $employeeDto->getSupervisor();

            return !in_array($supervisor, $employees);
        });

        return $this->findBossName($bossDtos);
    }

    /**
     * Get boss name from employeedto that might have empty supervisor
     *
     * @param $employeeDto
     *
     * @return supervisor of employeeDto if it's not empty, otherwise return employee.
     */
    private function getValidBossName($employeeDto) {
        if ($employeeDto->getSupervisor() && trim($employeeDto->getSupervisor()) !== "") {
            return $employeeDto->getSupervisor();
        }

        return $employeeDto->getEmployee();
    }

    /**
     * Check that the employedtos input should have the same supervisor and return that supervisor
     *
     * @param $bossDtos employeeDtos whose supervisor might be the boss.
     *
     * @return name of the boss
     */
    private function findBossName($bossDtos) {
        if (count($bossDtos) == 0) {
            // there definitely will be at least one boss, this case never happens
            throw new InvalidArgumentException("Boss not found - Definitely there is no any data in json file");
        }

        // if there are many bosses found, they should be identical
        $bossNames = $bossDtos->map(function ($bossDto) {
            return $this->getValidBossName($bossDto);
        })->toArray();

        if (!CommonUtils::hasIdenticalElements($bossNames)) {
            throw new InvalidArgumentException("There is more than one top boss: " . implode(', ', array_unique($bossNames)));
        }

        return $this->getValidBossName($bossDtos->first());
    }

    public function findEmployeesUnderSupervisor($supervisor, $employeeDtos): array {
        return collect($employeeDtos)->filter(function ($data) use ($supervisor) {
            return $data->getSupervisor() === $supervisor;
        })->map(function ($data) {
            return $data->getEmployee();
        })->toArray();
    }

    public function buildTree($employeeNode, $employeeDtos) {
        $subordinates = $this->findEmployeesUnderSupervisor($employeeNode->getEmployeeName(), $employeeDtos);

        foreach ($subordinates as $subordinate) {
            $subEmpNode = new EmployeeNode($subordinate);
            $employeeNode->addSubordinate($subEmpNode);
            $this->buildTree($subEmpNode, $employeeDtos);
        }
    }
}