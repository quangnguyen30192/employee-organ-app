<?php

namespace App\Http\Controllers;

use App\DataProviders\EmployeeDataProvider;
use App\Helpers\CommonUtils;
use App\Services\EmployeeTreeService;
use Illuminate\Http\Request;
use InvalidArgumentException;

class IndexController extends Controller {

    private $employeeTreeService;
    private $employeeDataProvider;
    private $dataViewTypes;

    /**
     * IndexController constructor.
     */
    public function __construct(EmployeeTreeService $employeeTreeService, EmployeeDataProvider $employeeDataProvider) {
        $this->employeeTreeService = $employeeTreeService;
        $this->employeeDataProvider = $employeeDataProvider;
        $this->dataViewTypes = ["json", "chart"];
    }

    public function index() {
        return view('index')->with('dataViewTypes', $this->dataViewTypes);
    }

    public function upload(Request $request) {
        $file = $request->file('file');
        if (!$file) {
            return response()->json($this->createsErrorResponse('Failed to upload the file'));
        }

        $fileContent = file_get_contents($file);
        try {
            $employeeDtos = $this->employeeDataProvider->parseEmployeeData($fileContent);
            $boss = $this->employeeTreeService->findBoss($employeeDtos);

            $isChartView = $request->dataViewType === 'chart';
            $employeeRootNode = CommonUtils::createEmployeeNode($isChartView, $boss);
            $employeeTree = CommonUtils::createEmployeeTree($isChartView, $employeeRootNode, $this->employeeTreeService);

            $employeeTree->buildTreeOnRootNode($employeeDtos);

            return response()->json(CommonUtils::createsSuccessResponse($request->dataViewType, $employeeTree));
        } catch (InvalidArgumentException $exception) {
            $errorMessage = $exception->getMessage();
            return response()->json(CommonUtils::createsErrorResponse($errorMessage));
        }

    }
}
