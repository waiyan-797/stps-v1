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
        $trips = Trip::latest()->where('user_id', $user->id)->get();

        return response()->json($trips, 200);
    }

    // public function store(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'distance' => 'required|numeric',
    //         'duration' => 'required|string',
    //         'waiting_time' => 'required|string',
    //         'normal_fee' => 'required|numeric',
    //         'waiting_fee' => 'required|numeric',
    //         'extra_fee' => 'required|numeric',
    //         'total_cost' => 'required|numeric',
    //         'start_lat' => 'required|numeric',
    //         'start_lng' => 'required|numeric',
    //         'end_lat' => 'required|numeric',
    //         'end_lng' => 'required|numeric',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 400);
    //     }
    //     $commissionFee = System::find(1)->commission_fee;

    //     $user = User::find(Auth::user()->id);

    //     if ($user->balance < $commissionFee) {
    //         return response()->json(['error' => ['Not enough balence']], 400);
    //     }

    //     $trip = Trip::create([
    //         'user_id' => Auth::user()->id,
    //         'distance' => $request->distance,
    //         'duration' => $request->duration,
    //         'waiting_time' => $request->waiting_time,
    //         'normal_fee' => $request->normal_fee,
    //         'waiting_fee' => $request->waiting_fee,
    //         'extra_fee' => $request->extra_fee,
    //         'total_cost' => $request->total_cost,
    //         'start_lat' => $request->start_lat,
    //         'start_lng' => $request->start_lng,
    //         'end_lat' => $request->end_lat,
    //         'end_lng' => $request->end_lng
    //     ]);


    //     $user->balance -= $commissionFee;
    //     $user->save();

    //     // Create a new transaction record
    //     Transaction::create([
    //         'user_id' => $trip->user_id,
    //         'staff_id' => 1,
    //         'amount' => $commissionFee,
    //         'income_outcome' => 'outcome',
    //     ]);
    //     $system = System::find(1);
    //     $system->balance += $commissionFee;
    //     $system->save();
    //     $trip->save();

    //     return response()->json(['trip' => $trip, 'message' => 'Trip created successfully'], 201);
    // }

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
        return response()->json($trip);

    }


    // public function store(Request $request)
    // {
    //     $trip = Trip::create($request->all());

    //     // Broadcast the new trip data using Laravel WebSockets
    //     broadcast(new TripCreated($trip))->toOthers();

    //     return response()->json($trip, 201);
    // }
}
