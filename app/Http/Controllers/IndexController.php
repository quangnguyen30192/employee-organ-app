<?php

namespace App\Http\Controllers;

use App\DataProviders\EmployeeDataProvider;
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

    public function getUpload() {
        return view('upload');
    }

    public function postUpload(Request $request) {
        $file = $request->file('file');
        if ($file) {
            $fileContent = file_get_contents($file);
            $employeeData = $this->employeeDataProvider->parseEmployeeData($fileContent);
            return dd($employeeData);
        }
        return "Upload Successfully";
    }
}
