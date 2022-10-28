<?php
namespace App\Services\CarApi;

use App\Models\CarMake;
use App\Models\CarModel;
use App\Models\ModelYear;
use Illuminate\Support\Facades\DB;

class CarApi{

    public function carStore($requestData){

        $inputsMake['name'] = $requestData['make'];
        $inputsModel['model_name'] = $requestData['model'];

        DB::beginTransaction();
        try{
            $MakeStore = CarMake::create($inputsMake);
            if($MakeStore){
                $inputsModel['make_id'] = $MakeStore->id;
                $modelStore = CarModel::create($inputsModel);
                DB::commit();
                return ["id"=> $modelStore->make_id ,"status" => 200];
            }
            else {
                DB::rollBack();
                return ["error" => "Something went wrong"];
            }
        }catch (\Exception $e) {
            DB::rollBack();
            return ["error" => $e->getMessage()];
        }

    }

    public function getCarByID($carId): array
    {
        try {
            $car = CarMake::select('id','name')
                ->with(['carModel'=>function($q){
                    $q->select('id','make_id','model_name');
                        $q->with(['modelYears'=>function($q){
                            $q->select('id','model_id','year_num');
                            $q->whereNull('deleted_at');
                        }])->whereNull('deleted_at');
            }])->where(['id' => $carId])->whereNull('deleted_at')->first()->toArray();

            foreach($car['car_model']['model_years'] as $key => $years){
                $yearArray[] = $years['year_num'];
            }

            $carInfo = [
                'id' => $car['id'],
                'info' => $car['name'] . " " . $car['car_model']['model_name'],
                'years' => $yearArray
            ];

            if($carInfo){
                return $carInfo;
            }
            else{
                return ["error" => "No car found!"];
            }
        }
        catch (\Exception $e){
            return ["error" => $e->getMessage()];
        }
    }

    public function yearStore($carId, $requestData){

        $carWithModel = CarMake::select('id','name')
            ->with(['carModel'=>function($q){
                $q->select('id','make_id');
            }])->where(['id' => $carId])->whereNull('deleted_at')->first()->toArray();

        if($carWithModel){

            DB::beginTransaction();
            try{
                foreach($requestData['years'] as $year){
                    $modelYearStore = ModelYear::create([
                        'model_id' => $carWithModel['car_model']['id'],
                        'year_num' => $year,
                        'expiry' => $requestData['expiry'],
                    ]);
                }

                if($modelYearStore){
                    DB::commit();
                    return ['success' => true ,"status" => 200];
                }
                else {
                    DB::rollBack();
                    return ['success' => false ,"error" => "Something went wrong"];
                }

            }catch (\Exception $e) {
                DB::rollBack();
                return ["error" => $e->getMessage()];
            }
        }else{
            return ["error" => "No car data found"];
        }

    }

    public function getCarByYearData($requestData){

        $yearArray = explode(",",$requestData);

        $datas = CarMake::select('id','name')
            ->with(['carModel'=>function($q) use ($yearArray) {
                $q->select('id','make_id','model_name');
                $q->with(['modelYears'=>function($q) use ($yearArray) {
                    $q->select('id','model_id','year_num');
                    $q->whereNull('deleted_at');
                    $q->whereIn('year_num',$yearArray);
                    $q->orderBy('year_num','asc');
                }])->has('modelYears')->whereNull('deleted_at');
            }])->whereNull('deleted_at')->has('carModel')->get()->toArray();
        foreach($datas as $data){
            foreach($data['car_model']['model_years'] as $years){
                $yearsData[] = $years['year_num'];
            }
            $eachData = [
                'id' => $data['id'],
                'name' => $data['name'] ." ". $data['car_model']['model_name'] ." ". $data['car_model']['model_years'][0]['year_num'],
                'years' => $yearsData,
            ];
            $finalData['cars'][] = $eachData;
        }

        return $finalData;
    }

}

