<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/13/18
 * Time: 2:36 PM
 */

namespace App\Models;

/**
 * Class represents a node in an employee hierarchy chart.
 */
class EmployeeChartNode extends EmployeeNode {

    public function __construct(string $employeeName) {
        parent::__construct($employeeName);
    }

    /**
     * Specify data which should be serialized to JSON.
     * The json output format would conform the require of json format of OrganChart (https://github.com/dabeng/OrgChart)
     */
    public function jsonSerialize() {
        return [
            'name' => $this->getEmployeeName(),
            'children' => $this->getSubordinates()
        ];
    }
}