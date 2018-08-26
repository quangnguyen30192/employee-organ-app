<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/13/18
 * Time: 11:05 PM
 */

namespace App\Services;

use App\Helpers\CommonUtils;
use App\Models\EmployeeDto;
use InvalidArgumentException;
use Seld\JsonLint\DuplicateKeyException;
use Seld\JsonLint\JsonParser;

/**
 * Class that provides functionalities to works with the json input
 */
class EmployeeDataProvider {

    private $jsonParser;

    /**
     * Constructor
     */
    public function __construct() {
        $this->jsonParser = new JsonParser;
    }

    /**
     * Parse the json string input and convert them into an array of dto objects
     *
     * @param $data json string input or associative array represents key and value of json data.
     *
     * @return array of EmployeeDtos
     *
     * @throws InvalidArgumentException if the json string input is invalid or if the json has duplicate keys
     */
    public function parseEmployeeData($data): array {
        $array = is_string($data) ? $this->validateJson($data) : $data;

        $employeeDtos = [];
        foreach ($array as $key => $value) {
            $value = $this->convertToEmptyIfNullOrEmptyString($value);
            $this->validateKeyValue($key, $value);

            $employeeDtos[] = new EmployeeDto($key, $value);
        }

        return $employeeDtos;
    }

    /**
     * if the input json value is null or empty string, then convert to empty string
     *
     * @param $value the value of the json element
     *
     * @return empty string or the value input if it's neither null nor a string with empty value
     */
    private function convertToEmptyIfNullOrEmptyString($value) {
        if ($value === null || (is_string($value) && CommonUtils::isEmptyOrBlank($value))) {
            return "";
        }
        return $value;
    }

    /**
     * Validate the input which is the pair of value (supervisor) and key (employee), the value should be a string
     *
     * @throws InvalidArgumentException if the value is neither string nor an object or array (consider there are
     * nested multi-dimensional in the json input)
     *
     * @param $key
     * @param $value
     */
    private function validateKeyValue(string $key, $value): void {
        if (!is_string($value)) {
            if (is_object($value) || is_array($value)) {
                throw new InvalidArgumentException('Json file content should not contain nested multi-dimensional json');
            }

            throw new InvalidArgumentException("Value is not a string - for Key: $key");
        }

        if (CommonUtils::isEmptyOrBlank($key)) {
            $errorMsg = 'Json data contains empty key' . ($value === '' ? ' which also has empty value' : " which has the value: $value");
            throw new InvalidArgumentException($errorMsg);
        }

        if ($key === $value) {
            throw new InvalidArgumentException("Employee and supervisor have the same name: '$key'");
        }
    }

    /**
     * Validate the json string input has valid json form
     *
     * To check the key duplication, we're using: https://github.com/zaach/jsonlint
     *
     * @param $json
     *
     * @return associative array decoded from the valid json
     *
     * @throws InvalidArgumentException if the json string is not valid or has key duplication
     */
    private function validateJson(string $json): array {
        $arr = CommonUtils::isValidJson($json);
        if (!$arr) {
            throw new InvalidArgumentException('Json string input is not valid');
        }

        try {
            // check key duplication in json
            $this->jsonParser->parse($json, JsonParser::DETECT_KEY_CONFLICTS);
        } catch (DuplicateKeyException $e) {
            $details = $e->getDetails();
            throw new InvalidArgumentException('Duplicate employee ' . $details['key'] . ' at line ' . $details['line']);
        }

        return $arr;
    }

}