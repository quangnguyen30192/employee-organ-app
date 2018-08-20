<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/13/18
 * Time: 2:33 PM
 */

namespace App\Services;

use App\Helpers\CommonUtils;
use InvalidArgumentException;

/**
 * The class that provides functionalities to work with the employee tree
 */
class EmployeeTreeService {

    public function __construct() {

    }

    /**
     * Find the boss on the top and the employee hierarchy
     * The boss is the employee who has no supervisors
     *
     * @param $employeeDtos an array of EmployeeDto
     *
     * @return name of the boss
     *
     * @throws InvalidArgumentException if there is more than one boss found
     */
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

    /**
     * Find the subordinate employees of the input supervisor/employee
     *
     * @param supervisor/employee name
     * @param $employeeDtos array of EmployeeDto
     *
     * @return array of employee names supervised by the input employee
     */
    public function findEmployeesUnderSupervisor($supervisor, $employeeDtos): array {
        return collect($employeeDtos)->filter(function ($employeeDto) use ($supervisor) {
            return $employeeDto->getSupervisor() === $supervisor;
        })->map(function ($employeeDto) {
            return $employeeDto->getEmployee();
        })->toArray();
    }
}