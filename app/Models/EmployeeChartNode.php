<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/13/18
 * Time: 2:36 PM
 */

namespace App\Models;


/**
 * Class represents a node in EmployeeChart
 */
class EmployeeChartNode extends EmployeeNode {

    public function __construct($employeeName) {
        parent::__construct($employeeName);
    }

    /**
     * Specify data which should be serialized to JSON.
     * The json output format would conform the require of json format of OrganChart (https://github.com/dabeng/OrgChart)
     */
    public function jsonSerialize() {
        return [
            'name' => $this->employeeName,
            'children' => $this->subordinates
        ];
    }
}