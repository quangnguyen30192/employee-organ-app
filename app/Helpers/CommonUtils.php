<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/13/18
 * Time: 6:18 PM
 */

namespace App\Helpers;


class CommonUtils {

    /**
     * Check the json input is valid
     *
     * @param $jsonString json string input
     *
     * @return false if the input json string is not valid, otherwise an associative array decoded from
     * json input will be returned
     */
    public static function isValidJson($jsonString) {
        if (is_string($jsonString) && trim($jsonString) !== '') {
            $array = json_decode($jsonString, true);

            return json_last_error() === JSON_ERROR_NONE && is_array($array) ? $array : false;
        }

        return false;
    }
}