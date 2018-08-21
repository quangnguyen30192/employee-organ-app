<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/18/18
 * Time: 2:41 AM
 */

namespace App\Helpers;

use App\Models\Chart\EmployeeChart;
use App\Models\Chart\EmployeeChartNode;
use App\Models\EmployeeNode;
use App\Models\EmployeeTree;
use Illuminate\Container\Container;

/**
 * The factory that provides EmployeeTree
 * @package App
 */
class EmployeeTreeFactory {

    private function __construct() {
    }

    /**
     * Factory method to create EmployeeTree base on data view type
     *
     * @param dataViewType json or chart
     * @param $employeeName
     *
     * @return EmployeeTree
     */
    public static function createTree(string $dataViewType, string $employeeName): EmployeeTree {
        if ($dataViewType === "json") {
            return self::createEmployeeTree($employeeName);
        }

        return self::createEmployeeChart($employeeName);
    }

    /**
     * Create EmployeeTree from IoC container in which also provides EmployeeTreeService that already bind rather than
     * using new operator
     *
     * @param $employeeName
     *
     * @return EmployeeTree
     */
    private static function createEmployeeTree(string $employeeName): EmployeeTree {
        return Container::getInstance()->make(EmployeeTree::class, [
            'employeeRootNode' => new EmployeeNode($employeeName)
        ]);
    }

    private static function createEmployeeChart(string $employeeName): EmployeeTree {
        return Container::getInstance()->make(EmployeeChart::class, [
            'employeeRootNode' => new EmployeeChartNode($employeeName)
        ]);
    }
}