<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/13/18
 * Time: 6:18 PM
 */

namespace App\Helpers;


use App\Models\EmployeeTree;

class CommonUtils {

    /**
     * Prevent creating instances from outside utilities method.
     */
    private function __construct() {
    }


    /**
     * Check the json input is valid
     *
     * @param $json json string input
     *
     * @return false if the input json string is not valid, otherwise an associative array decoded from
     * json input will be returned
     */
    public static function isValidJson(string $json) {
        $array = json_decode($json, true);

        return json_last_error() === JSON_ERROR_NONE && is_array($array) ? $array : false;
    }

    /**
     * Check that all elements in an array of strings are identical
     *
     * @param $array array of strings
     *
     * @return true if all elements in an array of strings are identical, false for otherwise or the array is not valid
     */
    public static function hasIdenticalElements(array $array): bool {
        if (count($array) == 0) {
            return false;
        }

        $firstElement = array_pop($array);
        foreach ($array as $element) {
            if ($element !== $firstElement) {
                return false;
            }
        }

        return true;
    }

    /**
     * Create successful data response using in Controller
     *
     * @param $dataViewType json or chart
     * @param $employeeTree
     *
     * @return array represents successful data response
     */
    public static function createsSuccessResponse(string $dataViewType, EmployeeTree $employeeTree): array {
        return [
            'status' => 'success',
            'result' => [
                'dataViewType' => $dataViewType,
                'data' => $employeeTree,
            ],
            'message' => 'Load data successfully'
        ];
    }

    /**
     * Create error data response using in Controller
     *
     * @param $errorMessage
     *
     * @return array represents error data response
     */
    public static function createsErrorResponse(string $errorMessage): array {
        return [
            'status' => 'error',
            'result' => [
                'dataViewType' => '',
                'data' => [],
            ],
            'message' => $errorMessage
        ];
    }
}