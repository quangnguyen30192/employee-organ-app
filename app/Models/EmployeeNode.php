<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/13/18
 * Time: 2:36 PM
 */

namespace App\Models;


use JsonSerializable;

/**
 * Class EmployeeNode represents a node in an employee hierarchy tree
 */
class EmployeeNode implements JsonSerializable {

    private $employeeName;
    private $subordinates;

    public function __construct(string $employeeName) {
        $this->employeeName = $employeeName;
        $this->subordinates = [];
    }

    public function addSubordinate(EmployeeNode $employeeNode): void {
        $this->subordinates[] = $employeeNode;
    }

    public function getEmployeeName(): string {
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