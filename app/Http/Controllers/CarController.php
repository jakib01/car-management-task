<?php

namespace App\Http\Controllers;

use App\Services\CarApi\CarApi;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index(CarApi $api){

        $data = $api->getTest();
        dd($data);
    }
}
