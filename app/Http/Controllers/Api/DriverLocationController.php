<?php

namespace App\Http\Controllers\Api;

use App\Events\DriverEvent;
use App\Events\DriverLocationEvent;
use App\Http\Controllers\Controller;
use App\Models\CarType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Broadcast;
class DriverLocationController extends Controller
{

    public function show($id){
        $driver = User::findOrFail($id);
        return response()->json($driver);

    }

    public function driverall(){
        $driver = User::role('user')->get();
        
        
        return response()->json($driver);
        
        
    }

    public function cartype(){
        $cartypes = CarType::all();

        return response()->json($cartypes);
        
    }


    
    public function driverupdate(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user = User::findOrFail($id);

        $user->lat = $request->lat;
        $user->lng = $request->lng;

        $user->save();

        $data = [
            'id'=> $user->id,
            'lat'=>$request->lat,
            'lng' =>$request->lng
        ];
        // broadcast(new DriverEvent($data));

        return response()->json(['driver'=>$user ,'message' => 'Driver updated successfully'], 200);
    }
}
