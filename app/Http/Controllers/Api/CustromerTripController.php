<?php

namespace App\Http\Controllers\Api;

use App\Events\TripCreated;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Events\TripEvent;

class CustromerTripController extends Controller
{
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
            'start_lat' => 'required|numeric',
            'start_lng' => 'required|numeric',
            'end_lat' => 'required|numeric',
            'end_lng' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        
        

        $trip = Trip::create([
            'user_id' => Auth::user()->id,
            'distance' => $request->distance,
            'duration' => $request->duration,
            'waiting_time' => $request->waiting_time,
            'normal_fee' => $request->normal_fee,
            'waiting_fee' => $request->waiting_fee,
            'extra_fee' => $request->extra_fee,
            'total_cost' => $request->total_cost,
            'start_lat' => $request->start_lat,
            'start_lng' => $request->start_lng,
            'end_lat' => $request->end_lat,
            'end_lng' => $request->end_lng,
             'start_address' => $request->start_address,
             'end_address' => $request->end_address
        ]);

        $trip = [
            'user_id' => Auth::user()->id,
            'distance' => $request->distance,
            'duration' => $request->duration,
            'waiting_time' => $request->waiting_time,
            'normal_fee' => $request->normal_fee,
            'waiting_fee' => $request->waiting_fee,
            'extra_fee' => $request->extra_fee,
            'total_cost' => $request->total_cost,
            'start_lat' => $request->start_lat,
            'start_lng' => $request->start_lng,
            'end_lat' => $request->end_lat,
            'end_lng' => $request->end_lng,
            'start_address' => $request->start_address,
            'end_address' => $request->end_address
        ];

        broadcast(new TripEvent($trip));

      
       

        return response()->json(['trip' => $trip, 'message' => 'Trip created successfully'], 201);
    }

    public function update(Request $request, $id)
    {

        // dd($request);
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

        // if ($validator->fails()) {
        //     return response()->json(['error' => $validator->errors()], 400);
        // }

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
        // $trip->end_lng = $request->start_address;
        // $trip->end_lng = $request->end_address;
        // $trip->status = $request->status;
        // $trip->driver_id = $request->driver_id;


        $trip->save();

        $trip = [
            'user_id' => Auth::user()->id,
            'distance' => $request->distance,
            'duration' => $request->duration,
            'waiting_time' => $request->waiting_time,
            'normal_fee' => $request->normal_fee,
            'waiting_fee' => $request->waiting_fee,
            'extra_fee' => $request->extra_fee,
            'total_cost' => $request->total_cost,
            'start_lat' => $request->start_lat,
            'start_lng' => $request->start_lng,
            'end_lat' => $request->end_lat,
            'end_lng' => $request->end_lng,
            'status' => $request->status,
            'driver_id' =>$request->driver_id,
         
            
        ];

        broadcast(new TripEvent($trip));

        return response()->json(['message' => 'Trip updated successfully'], 200);
    }

    public function destroy($id)
    {
        $trip = Trip::findOrFail($id);

        if (!$trip) {
            return response()->json(['error' => 'Trip not found'], 404);
        }

        $trip->delete();

        return response()->json(['message' => 'Trip deleted successfully'], 200);
    }
}
