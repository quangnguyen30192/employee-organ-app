<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{

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
