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
     * @param $json json string input
     *
     * @return array of EmployeeDtos
     *
     * @throws InvalidArgumentException if the json string input is invalid or if the json has duplicate keys
     */
    public function parseEmployeeData(string $json): array {
        $array = $this->validateJson($json);

        $employeeDtos = [];
        foreach ($array as $key => $value) {
            $value = CommonUtils::isEmptyOrBlankStringOrNull($value) ? '' : $value;
            $this->validateKeyValue($key, $value);

            $employeeDtos[] = new EmployeeDto(trim($key), trim($value));
        }

        return $employeeDtos;
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
        $this->validateKey($key, $value);

        $this->validateValue($key, $value);

        if ($key === $value) {
            throw new InvalidArgumentException("Employee and supervisor have the same name: '$key'");
        }
    }

    private function validateKey(string $key, $value) {
        if (CommonUtils::isEmptyOrBlankStringOrNull($key)) {
            $errorMsg = 'Json data contains empty key';
            if (CommonUtils::isEmptyOrBlankStringOrNull($value)) {
                $errorMsg .= ' where also has empty value';
            } else {
                $errorMsg .= " where has the value: " . CommonUtils::trimStringOrValue($value);
            }
            throw new InvalidArgumentException($errorMsg);
        }
    }

    private function validateValue(string $key, $value) {
        if (!is_string($value)) {
            if (is_object($value) || is_array($value)) {
                throw new InvalidArgumentException('Json file content should not contain nested multi-dimensional json');
            }

            throw new InvalidArgumentException("Value is not a string - for Key: $key");
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