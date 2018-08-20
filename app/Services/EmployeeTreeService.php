<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/13/18
 * Time: 10:54 PM
 */

namespace App\Services;

/**
 * Interface provides the implementation to work with employee hierarchy organization tree
 */
interface EmployeeTreeService {

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
    public function findBoss($employeeDtos);

    /**
     * Find the subordinate employees of the input supervisor/employee
     *
     * @param supervisor/employee name
     * @param $employeeDtos an array of EmployeeDto
     *
     * @return array of employee names supervised by the input employee
     */
    public function findEmployeesUnderSupervisor($supervisor, $employeeDtos);
}