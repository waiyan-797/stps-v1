<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\System;
use App\Models\Transaction;
use App\Events\TripCreated;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TripController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $trips = Trip::latest()->where('driver_id', $user->id)->get();

        return response()->json($trips, 200);
    }

    public function tripStart(Request $request)
    {
        $driverid = $request->driver_id;

        $trip = Trip::where('driver_id',$driverid)->where('status','accepted')->get();

    

        return response()->json($trip);
    }


    //trip end 
    public function store(Request $request)
    {

    
        $validator = Validator::make($request->all(), [
            'distance' => 'required|numeric',
            'duration' => 'required|string',
            'waiting_time' => 'required|string',
            'normal_fee' => 'required|numeric',
            'waiting_fee' => 'required|numeric',
            'extra_fee' => 'required|numeric',
            'total_cost' => 'required|numeric',
            'end_lat' => 'required|numeric',
            'end_lng' => 'required|numeric',
            // 'status' =>'required',
            'trip_id'=> 'nullable'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }


        $driver = User::findOrFail(Auth::user()->id);
    

    if($driver->available == false || $driver->available == 0){
        $driver->available = true;

        $driver->save();
    }

       
        if($request->trip_id == null || $request->trip_id == 'null'){
            
            
    
            
            $trip = new Trip();
    
            
            $trip->user_id = null;
            $trip->distance = $request->distance;
            $trip->duration = $request->duration;
            $trip->waiting_time = $request->waiting_time;
            $trip->normal_fee = $request->normal_fee;
            $trip->waiting_fee = $request->waiting_fee;
            $trip->extra_fee = $request->extra_fee;
            $trip->total_cost = $request->total_cost;
            $trip->end_lat = $request->end_lat;
            $trip->end_lng = $request->end_lng;
            $trip->status = "completed";
            $trip->start_address = $request->start_address;
            $trip->end_address = $request->end_address;
            $trip->driver_id = $driver->id;
            $trip->cartype = $request->cartype;
    

            $system =  System::find(1);
            $total = $request->total_cost; // Total amount
            // $percentage = 10; // Percentage to calculate
            
            // Calculate the percentage amount
            // $percentageAmount = $total - $system->commission_fee;
            
            // Update user's balance
            $driver->balance -= $system->commission_fee;
    
            $driver->save();
    
    
            // Create a new transaction record
            Transaction::create([
                'user_id' => $trip->driver_id,
                'staff_id' => 1,
                'amount' => $system->commission_fee,
                'income_outcome' => 'outcome',
            ]);
            $system = System::find(1);
            $system->balance += $system->commission_fee;
            $system->save();
            $trip->save();

           
    
            return response()->json($trip);
        }else{
                    $trip = Trip::findOrFail($request->trip_id);
            
                    if (!$trip) {
                        return response()->json(['error' => 'Trip not found'], 404);
                    }

            $trip->distance = $request->distance;
            $trip->duration = $request->duration;
            $trip->waiting_time = $request->waiting_time;
            $trip->normal_fee = $request->normal_fee;
            $trip->waiting_fee = $request->waiting_fee;
            $trip->extra_fee = $request->extra_fee;
            $trip->total_cost = $request->total_cost;
            $trip->end_lat = $request->end_lat;
            $trip->end_lng = $request->end_lng;
            $trip->status = "completed";
            $trip->start_address = $request->start_address;
            $trip->end_address = $request->end_address;
            $trip->driver_id = $driver->id;
            $system = System::findOrFail(1);

            $initial_fee=0;

            if($trip->cartype == 1 ){
            $initial_fee=$system->standard_fee;

            }elseif($trip->cartype == 2){
            $initial_fee=$system->cargo_fee;

            }else{
            $initial_fee=$system->plus_fee;

            }

            $trip->initial_fee = $initial_fee;
    
           
            $totalCost = $request->total_cost; // Total amount
            $total = $totalCost + $initial_fee;
            $percentage = $system->order_commission_fee; // Percentage to calculate
            
            // Calculate the percentage amount
            $percentageAmount = ($percentage / 100) * $total;
            
            // Update user's balance
            $driver->balance -= $percentageAmount;
    
            $driver->save();
    
    
            // Create a new transaction record
            Transaction::create([
                'user_id' => $trip->driver_id,
                'staff_id' => 1,
                'amount' => $percentageAmount,
                'income_outcome' => 'outcome',
            ]);
            $system = System::find(1);
            $system->balance += $percentageAmount;
            $system->save();
            $trip->save();
            return response()->json($trip);


                }

                
            
                   
           
    }

    public function show($id)
    {

        
        $trip = Trip::find($id);

        if (!$trip) {
            return response()->json(['message' => 'Trip not found'], 422);
        }

        return response()->json($trip, 200);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'distance' => 'required|numeric',
            'duration' => 'required|string',
            'waiting_time' => 'required|string',
            'normal_fee' => 'required|numeric',
            'waiting_fee' => 'required|numeric',
            'extra_fee' => 'required|numeric',
            'total_cost' => 'required|numeric',
            'start_lat' => 'required|numeric',
            'start_lng' => 'required|numeric',
            'end_lat' => 'required|numeric',
            'end_lng' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $trip = Trip::find($id);

        if (!$trip) {
            return response()->json(['error' => 'Trip not found'], 404);
        }

        $trip->distance = $request->distance;
        $trip->duration = $request->duration;
        $trip->waiting_time = $request->waiting_time;
        $trip->normal_fee = $request->normal_fee;
        $trip->waiting_fee = $request->waiting_fee;
        $trip->extra_fee = $request->extra_fee;
        $trip->total_cost = $request->total_cost;
        $trip->start_lat = $request->start_lat;
        $trip->start_lng = $request->start_lng;
        $trip->end_lat = $request->end_lat;
        $trip->end_lng = $request->start_lng;

        $trip->save();

        return response()->json(['message' => 'Trip updated successfully'], 200);
    }


    public function destroy($id)
    {
        $trip = Trip::find($id);

        if (!$trip) {
            return response()->json(['error' => 'Trip not found'], 404);
        }

        $trip->delete();

        return response()->json(['message' => 'Trip deleted successfully'], 200);
    }


    public function tripStatusupdate(Request $request,$id){

        $validator = Validator::make($request->all(), [
            'status' => 'required',
            
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        
        $trip = Trip::findOrFail($id);

        $trip->status = $request->status;
        $trip->save();
        
        $driver = User::findOrFail($trip->driver_id);
        

        return response()->json($trip);

    }


    // public function store(Request $request)
    // {
    //     $trip = Trip::create($request->all());

    //     // Broadcast the new trip data using Laravel WebSockets
    //     // broadcast(new TripCreated($trip))->toOthers();

        

    //     return response()->json($trip, 201);
    // }
}
