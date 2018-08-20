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
     * Constructor.
     *
     * @param $employee
     * @param $supervisor
     */
    public function __construct($employee, $supervisor) {
        $this->employee = $employee;
        $this->supervisor = $supervisor;
    }

    public function getEmployee() {
        return $this->employee;
    }

    public function getSupervisor() {
        return $this->supervisor;
    }
}