<?php

namespace App\Http\Controllers;

use App\Helpers\CommonUtils;
use App\Services\EmployeeDataProvider;
use App\TreeBuilders\EmployeeTreeBuilderFactory;
use Illuminate\Http\Request;
use InvalidArgumentException;

class EmployeeController extends Controller {

    private $employeeDataProvider;

    /**
     * EmployeeController constructor.
     */
    public function __construct(EmployeeDataProvider $employeeDataProvider) {
        $this->employeeDataProvider = $employeeDataProvider;
    }

    public function index() {
        return view('index')->with('dataViewTypes', ['json', 'chart']);
    }

    /**
     * Handle json file upload that contains the employee data as json format
     *
     * @param Request $request
     *
     * @return json that represents the employee data or error message in case of invalid json input
     */
    public function upload(Request $request) {
        $file = $request->file('file');
        if (!$file) {
            return response()->json(CommonUtils::createsErrorResponse('Failed to upload the file'));
        }

        $fileContent = file_get_contents($request->file('file'));
        try {
            $employeeTreeBuilder = EmployeeTreeBuilderFactory::createTreeBuilder($request->dataViewType);

            $employeeDtos = $this->employeeDataProvider->parseEmployeeData($fileContent);
            $employeeTree = $employeeTreeBuilder->buildTree($employeeDtos);

            return response()->json(CommonUtils::createsSuccessResponse($request->dataViewType, $employeeTree));
        } catch (InvalidArgumentException $exception) {
            return response()->json(CommonUtils::createsErrorResponse($exception->getMessage()));
        }
    }

    /**
     * API handles the Json POST request
     *
     * @param Request $request
     *
     * @return json that represents the employee data or error message in case of invalid json input
     */
    public function employeeJsonApi(Request $request) {
        try {
            $employeeTreeBuilder = EmployeeTreeBuilderFactory::createTreeBuilder();

            /*
             * Use $request->getContent() to get json request as a string for proper validation instead of using $request->json()
             * which would return empty json body if the json request is invalid (e.g: not well-formed or key duplication,.. )
             */
            $json = $request->getContent();
            $employeeDtos = $this->employeeDataProvider->parseEmployeeData($json);

            $employeeTree = $employeeTreeBuilder->buildTree($employeeDtos);

            return response()->json($employeeTree);
        } catch (InvalidArgumentException $exception) {
            return response()->json(['error' => $exception->getMessage()]);
        }
    }

}
