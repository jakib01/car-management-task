<?php

namespace App\Http\Controllers;

use App\Services\CarApi\CarApi;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

class CarController extends Controller
{

    public function store(CarApi $carStore, Request $request): \Illuminate\Http\JsonResponse
    {
        $insertData = $carStore->carStore($request->all());

        return response()->json($insertData);
    }

    public function getCar(CarApi $data, $carId): \Illuminate\Http\JsonResponse
    {
        $carData = $data->getCarByID($carId);

        return response()->json($carData);
    }

    public function storeYears(CarApi $yearStore, $carId , Request $request): \Illuminate\Http\JsonResponse
    {
        $insertData = $yearStore->yearStore($carId, $request->all());

        return response()->json($insertData);
    }

    public function getCarByYear(CarApi $getByYear, Request $request){

        if(isset($request->years)){
            $data = $getByYear->getCarByYearData($request->years);
            return response()->json($data);
        }else{
            return response()->json(["error"=>"No data given"]);
        }

    }

}
