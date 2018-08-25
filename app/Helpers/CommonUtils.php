<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/13/18
 * Time: 6:18 PM
 */

namespace App\Helpers;


use App\Models\EmployeeNode;

/**
 * Utilities class that provides common functionalties to work with overall application
 */
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
     * Check the string input is empty or blank
     *
     * @param string $str
     *
     * @return true if the input string is empty or blank, false for otherwise
     */
    public static function isEmptyOrBlank(string $str): bool {
        return trim($str) === "";
    }

    /**
     * Create successful data response using in Controller
     *
     * @param $dataViewType json or chart
     * @param $employeeNode employee node represents for the employee hierarchy tree
     *
     * @return array represents successful data response
     */
    public static function createsSuccessResponse(string $dataViewType, EmployeeNode $employeeNode): array {
        return [
            'status' => 'success',
            'result' => [
                'dataViewType' => $dataViewType,
                'data' => $employeeNode,
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