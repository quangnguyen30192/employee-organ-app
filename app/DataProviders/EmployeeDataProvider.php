<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/13/18
 * Time: 10:51 PM
 */

namespace App\DataProviders;

/**
 * Interface provides the implementation to work with data source
 */
interface EmployeeDataProvider {

    /**
     * Parse the data source input and convert them into an array of dto objects
     *
     * @param $data input source
     *
     * @return an array of dtos
     *
     * @throws InvalidArgumentException if any errors
     */
    public function parseEmployeeData($data);
}