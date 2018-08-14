<?php

namespace App\Http\Controllers;

use App\DataProviders\EmployeeDataProvider;
use App\EmployeeNode;
use App\Services\EmployeeTreeService;
use Illuminate\Http\Request;

class IndexController extends Controller
{

    private $employeeTreeService;
    private $employeeDataProvider;

    /**
     * IndexController constructor.
     */
    public function __construct(EmployeeTreeService $employeeTreeService, EmployeeDataProvider $employeeDataProvider) {
        $this->employeeTreeService = $employeeTreeService;
        $this->employeeDataProvider = $employeeDataProvider;
    }

    public function index() {
        return view('index');
    }

    public function upload(Request $request) {
        $file = $request->file('file');
        if ($file) {
            $fileContent = file_get_contents($file);
            $employeeData = $this->employeeDataProvider->parseEmployeeData($fileContent);

            $bossName = $this->employeeTreeService->findBoss($employeeData);

            $boss = new EmployeeNode($bossName);
            $this->employeeTreeService->buildTree($boss, $employeeData);

            return response()->json([
                                        'status' => 'success',
                                        'data' => $boss,
                                        'message' => 'Load data successfully'
                                    ]);
        }

        return response()->json([
                                    'status' => 'error',
                                    'data' => null,
                                    'message' => 'Failed to upload the file'
                                ]);

    }
}
