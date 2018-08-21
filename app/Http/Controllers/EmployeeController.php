<?php

namespace App\Http\Controllers;

use App\DataProviders\EmployeeDataProvider;
use App\Factories\EmployeeTreeFactory;
use App\Helpers\CommonUtils;
use App\Services\EmployeeTreeService;
use Illuminate\Http\Request;
use InvalidArgumentException;

class EmployeeController extends Controller {

    private $employeeTreeService;
    private $employeeDataProvider;

    /**
     * EmployeeController constructor.
     */
    public function __construct(EmployeeTreeService $employeeTreeService, EmployeeDataProvider $employeeDataProvider) {
        $this->employeeTreeService = $employeeTreeService;
        $this->employeeDataProvider = $employeeDataProvider;
    }

    public function index() {
        return view('index')->with('dataViewTypes', ["json", "chart"]);
    }

    public function upload(Request $request) {
        $file = $request->file('file');
        if (!$file) {
            return response()->json(CommonUtils::createsErrorResponse('Failed to upload the file'));
        }

        $fileContent = file_get_contents($request->file('file'));
        try {
            $employeeDtos = $this->employeeDataProvider->parseEmployeeData($fileContent);
            $boss = $this->employeeTreeService->findBoss($employeeDtos);

            $employeeTree = EmployeeTreeFactory::createTree($request->dataViewType, $boss);
            $employeeTree->buildTreeOnRootNode($employeeDtos);

            return response()->json(CommonUtils::createsSuccessResponse($request->dataViewType, $employeeTree));
        } catch (InvalidArgumentException $exception) {
            $errorMessage = $exception->getMessage();
            return response()->json(CommonUtils::createsErrorResponse($errorMessage));
        }
    }

    public function employeeJsonApi(Request $request) {
        $jsonData = $request->except('_token');

        try {
            $employeeDtos = $this->employeeDataProvider->parseEmployeeData($jsonData);
            $boss = $this->employeeTreeService->findBoss($employeeDtos);

            $employeeTree = EmployeeTreeFactory::createTree($request->dataViewType, $boss);
            $employeeTree->buildTreeOnRootNode($employeeDtos);

            return response()->json($employeeTree->jsonSerialize());
        } catch (InvalidArgumentException $exception) {
            $errorMessage = $exception->getMessage();
            return response()->json(['error' => $errorMessage]);
        }
    }

}
