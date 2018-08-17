<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/13/18
 * Time: 2:36 PM
 */

namespace App;


use JsonSerializable;

/**
 * Class EmployeeNode represents a node in a employee hierarchy tree
 */
class EmployeeNode implements JsonSerializable {

    protected $employeeName;
    protected $subordinates;

    public function __construct($employeeName) {
        $this->employeeName = $employeeName;
        $this->subordinates = [];
    }

    public function addSubordinate($employeeNode) {
        $this->subordinates[] = $employeeNode;
    }

    public function getEmployeeName() {
        return $this->employeeName;
    }

    public function getSubordinates(): array {
        return $this->subordinates;
    }

    /**
     * Specify data which should be serialized to JSON.
     * The json output would have key as employeeName and value is its subordinates
     */
    public function jsonSerialize() {
        return [
            $this->employeeName => $this->subordinates
        ];
    }
}