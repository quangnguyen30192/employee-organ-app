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

    public function postUpload() {
        return "Upload Successfully";
    }
}
