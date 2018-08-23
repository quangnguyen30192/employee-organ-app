<?php

namespace App\Http\Controllers;

use App\Helpers\CommonUtils;
use App\Helpers\EmployeeTreeBuilderFactory;
use App\Services\EmployeeDataProvider;
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
        return view('index')->with('dataViewTypes', ["json", "chart"]);
    }

    /**
     * Handle json file upload that contains employee data as json format
     *
     * @param Request $request
     *
     * @return json that represents employee data or error message in case of invalid json input
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
            $errorMessage = $exception->getMessage();
            return response()->json(CommonUtils::createsErrorResponse($errorMessage));
        }
    }

    /**
     * API handles the Json POST request
     *
     * @param Request $request
     *
     * @return json that represents employee data or error message in case of invalid json input
     */
    public function employeeJsonApi(Request $request) {
        try {
            $employeeTreeBuilder = EmployeeTreeBuilderFactory::createTreeBuilder();

            $employeeDtos = $this->employeeDataProvider->parseEmployeeData($request->json());
            $employeeTree = $employeeTreeBuilder->buildTree($employeeDtos);

            return response()->json($employeeTree->jsonSerialize());
        } catch (InvalidArgumentException $exception) {
            $errorMessage = $exception->getMessage();
            return response()->json(['error' => $errorMessage]);
        }
    }

}
