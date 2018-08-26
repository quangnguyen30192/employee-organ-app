<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/18/18
 * Time: 2:41 AM
 */

namespace App\TreeBuilders;

use Illuminate\Container\Container;

/**
 * The factory that provides EmployeeTreeBuilderBase subclasses
 */
class EmployeeTreeBuilderFactory {

    /**
     * EmployeeTreeBuilderFactory constructor.
     *
     * Private constructor to prevent creating new instances outside this class.
     */
    private function __construct() {
    }

    /**
     * Factory method to create EmployeeTreeBuilderBase subclasses from data view type
     *
     * EmployeeTreeBuilderBase subclasses should be created from this factory method - not from its constructor
     *
     * @param dataViewType json or chart
     *
     * @return EmployeeTreeBuilderBase
     */
    public static function createTreeBuilder(string $dataViewType = 'json'): EmployeeTreeBuilderBase {
        if ($dataViewType === 'json') {
            // create EmployeeTreeBuilder from IoC container in which also provides EmployeeTreeService rather than using new operator
            return Container::getInstance()->make(EmployeeTreeBuilder::class);
        }

        return Container::getInstance()->make(EmployeeChartBuilder::class);
    }
}