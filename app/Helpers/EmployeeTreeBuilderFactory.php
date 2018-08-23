<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/18/18
 * Time: 2:41 AM
 */

namespace App\Helpers;

use App\Models\Chart\EmployeeChartBuilder;
use App\Models\EmployeeTreeBuilder;
use Illuminate\Container\Container;

/**
 * The factory that provides EmployeeTreeBuilder
 * @package App
 */
class EmployeeTreeBuilderFactory {

    private function __construct() {
    }

    /**
     * Factory method to create EmployeeTreeBuilder base on data view type
     *
     * @param dataViewType json or chart
     *
     * @return EmployeeTreeBuilder
     */
    public static function createTreeBuilder(string $dataViewType = 'json'): EmployeeTreeBuilder {
        if ($dataViewType === "json") {
            // create EmployeeTreeBuilder from IoC container in which also provides EmployeeTreeService rather than using new operator
            return Container::getInstance()->make(EmployeeTreeBuilder::class);
        }

        return Container::getInstance()->make(EmployeeChartBuilder::class);
    }
}