<?php
/**
 * Created by PhpStorm.
 * User: qnguyen
 * Date: 8/13/18
 * Time: 2:33 PM
 */

namespace App\Services;

use App\Helpers\CommonUtils;
use App\Models\EmployeeDto;
use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * Class that provides functionalities to work with the employee tree
 */
class EmployeeTreeService {

    public function __construct() {

    }

    /**
     * Find the boss on the top of the employee hierarchy
     * The boss is the employee who has no supervisors
     *
     * This method also detects if there is a loop in json files based on the data input
     *
     * @param $employeeDtos an array of EmployeeDto
     *
     * @return name of the boss
     *
     * @throws InvalidArgumentException if there is more than one boss found or no employee dtos provided or there is
     * a loop in json file input
     */
    public function findBoss(array $employeeDtos): string {
        if (count($employeeDtos) == 0) {
            throw new InvalidArgumentException('There is no employee dtos provided');
        }

        $employees = collect($employeeDtos)->map(function ($employeeDto) {
            return $employeeDto->getEmployee();
        })->toArray();

        // find the bosses who are the employees and have no supervisor
        $bossDtos = collect($employeeDtos)->filter(function ($employeeDto) use ($employees) {
            $supervisor = $employeeDto->getSupervisor();

            return !in_array($supervisor, $employees);
        });

        /*
         * This case happens because the top boss we're finding is also supervised by a subordinate employee -
         * this indicates that there is a loop in json data.
         *
         * There will be definitely at least one boss if the json data makes sense.
         */
        if (count($bossDtos) == 0) {
            throw new InvalidArgumentException('There is a loop in json');
        }

        return $this->findBossName($bossDtos);
    }

    /**
     * Get the boss name from employee dto
     * If the employee dto has no supervisor then boss name is the employee, otherwise the boss name is supervisor
     *
     * @param $employeeDto
     *
     * @return boss name
     */
    private function getValidBossName(EmployeeDto $employeeDto): string {
        $bossName = $employeeDto->getSupervisor();
        return CommonUtils::isEmptyOrBlank($bossName) ? $employeeDto->getEmployee() : $bossName;
    }

    /**
     * Check that the employee dtos input should only have the same supervisor and return that supervisor
     *
     * @param $employeeDtos employeeDtos whose supervisor might be the boss.
     *
     * @return name of the boss
     */
    private function findBossName(Collection $employeeDtos): string {

        // if there are many bosses found then they should be identical
        $bossNames = $employeeDtos->map(function ($bossDto) {
            return $this->getValidBossName($bossDto);
        })->toArray();

        if (!CommonUtils::hasIdenticalElements($bossNames)) {
            throw new InvalidArgumentException('There is more than one top boss: ' . implode(', ', array_unique($bossNames)));
        }

        return $this->getValidBossName($employeeDtos->first());
    }

    /**
     * Find the subordinate employees of the input supervisor
     *
     * @param supervisor
     * @param $employeeDtos
     *
     * @return array of employee names supervised by the input supervisor
     */
    public function findEmployeesUnderSupervisor(string $supervisor, array $employeeDtos): array {

        return collect($employeeDtos)->filter(function ($employeeDto) use ($supervisor) {
                return $employeeDto->getSupervisor() === $supervisor;
            })->map(function ($employeeDto) {
                return $employeeDto->getEmployee();
            })->values()->toArray();
    }
}