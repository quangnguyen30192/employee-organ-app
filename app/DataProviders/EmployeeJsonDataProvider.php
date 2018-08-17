<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/13/18
 * Time: 11:05 PM
 */

namespace App\DataProviders;

use App\EmployeeDto;
use App\Helpers\CommonUtils;
use InvalidArgumentException;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;
use Seld\JsonLint\DuplicateKeyException;
use Seld\JsonLint\JsonParser;

/**
 * The implementation of EmployeeDataProvider that works with the json input file
 */
class EmployeeJsonDataProvider implements EmployeeDataProvider {

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
     * @param $jsonString json string input
     *
     * @return an array of EmployeeDtos
     *
     * @throws InvalidArgumentException if the json string input is invalid or if the json has duplicate keys
     */
    public function parseEmployeeData($jsonString) {

        $json = CommonUtils::isValidJson($jsonString);
        if (!$json) {
            throw new InvalidArgumentException("Json string input is not valid");
        }

        try {
            $this->jsonParser->parse($jsonString, JsonParser::DETECT_KEY_CONFLICTS);
        } catch (DuplicateKeyException $e) {
            $details = $e->getDetails();
            throw new InvalidArgumentException('Duplicate key \'' . $details['key'] . '\' at line ' . $details['line']);
        }

        $jsonIterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($json), RecursiveIteratorIterator::SELF_FIRST);

        $employeeDtos = [];
        foreach ($jsonIterator as $key => $value) {
            $this->validateValue($key, $value);

            $employeeDtos[] = new EmployeeDto($key, $value);
        }

        return $employeeDtos;
    }

    /**
     * Validate the input which is the value (supervisor) of each key (employee), the value should be a string
     *
     * @throws InvalidArgumentException if the value is neither not string nor an object or array (consider there are
     * nested multi-dimensional in the json input)
     *
     * @param $value the value input
     */
    protected function validateValue($key, $value) {
        if (!is_string($value)) {
            if (is_object($value) || is_array($value)) {
                throw new InvalidArgumentException("Json file content should not contain nested multi-dimensional json");
            }

            throw new InvalidArgumentException("Value is not a string - for Key: $key");
        }
    }

}