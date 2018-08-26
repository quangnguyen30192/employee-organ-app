<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/12/18
 * Time: 3:23 PM
 */

namespace App\Models;


/**
 * Data transfer object class that represents for each pair key (as employee) and value (as supervisor)
 * that was parsed from the content of json file input
 */
class EmployeeDto {

    private $employee;
    private $supervisor;

    /**
     * EmployeeDto constructor.
     *
     * @param $employee
     * @param $supervisor
     */
    public function __construct(string $employee, string $supervisor) {
        $this->employee = $employee;
        $this->supervisor = $supervisor;
    }

    public function getEmployee(): string {
        return $this->employee;
    }

    public function getSupervisor(): string {
        return $this->supervisor;
    }
}