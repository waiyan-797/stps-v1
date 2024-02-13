<?php

namespace App\Http\Controllers\Api;

use App\Events\TripEvent;
use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



class CustromerTripController extends Controller
{
    public function index(){
        $trips = Trip::all();
        return response()->json($trips);
    }

    public function show($id){
        $trip = Trip::findOrFail($id);
        if($trip->driver_id !== null){
            $driver = User::findOrFail($trip->driver_id);
            $trip->driver_id = $driver;
            
            $data = [
                "id"=> $trip->id,
                "user_id" => $trip->user_id,
                "distance"=> $trip->distance,
                "duration"=> $trip->duration,
                "waiting_time"=> $trip->waiting_time,
                "normal_fee"=> $trip->normal_fee,
                "waiting_fee"=>  $trip->waiting_fee,
                "extra_fee"=> $trip->extra_fee,
                "total_cost"=> $trip->total_cost,
                "start_lat"=> $trip->start_lat,
                "start_lng"=> $trip->start_lng,
                "end_lat"=> $trip->end_lat,
                "end_lng"=>  $trip->end_lng,
                "status"=> $trip->status,
                "start_address"=>  $trip->start_address,
                "end_address"=>  $trip->end_address,
                "driver"=> $driver,
                "cartype"=> $trip->cartype,
                "created_at"=>  $trip->created_at,
                "updated_at"=>  $trip->created_at
            ];

                return response()->json($data);

        }
        return response()->json($trip);
       
    }
   
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
            'cartype' => 'required|numeric',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        
        // $trip =new Trip();
        // $trip->user_id =Auth::user()->id;
        // $trip->distance=$request->distance;
        // $trip->duration=$request->distance;
        // $trip->waiting_time=$request->normal_fee;
        // $trip->normal_fee=$request->normal_fee;
        // $trip->waiting_fee=$request->waiting_fee;
        // $trip->extra_fee=$request->extra_fee;
        // $trip->total_cost=$request->total_cost;
        // $trip->start_lat= $request->start_lat;
        // $trip->start_lng=$request->start_ang;
        // $trip->end_lat=$request->end_lat;
        // $trip->end_lng=$request->end_lng;
        // $trip->start_address= $request->start_address;
        // $trip->end_address= $request->end_address;
        // $trip->cartype= $request->cartype;  
        

        

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
             'end_address' => $request->end_address,
             'cartype' => $request->type

        ]);

        // $trips = [
        //     'id' =>$trip->id,
        //     'user_id' => Auth::user()->id,
        //     'distance' => $request->distance,
        //     'duration' => $request->duration,
        //     'waiting_time' => $request->waiting_time,
        //     'normal_fee' => $request->normal_fee,
        //     'waiting_fee' => $request->waiting_fee,
        //     'extra_fee' => $request->extra_fee,
        //     'total_cost' => $request->total_cost,
        //     'start_lat' => $request->start_lat,
        //     'start_lng' => $request->start_lng,
        //     'end_lat' => $request->end_lat,
        //     'end_lng' => $request->end_lng,
        //     'start_address' => $request->start_address,
        //     'end_address' => $request->end_address,
        //     'cartype' => $request->type

        // ];

        $radius = 2; // Initial radius
        $nearestDriver = $this->searchNearbyDrivers($request,$radius);


        if (!$nearestDriver) {
            $radius = 3; // Extend radius to 3 km
            $nearestDriver = $this->searchNearbyDrivers($request,$radius);
        }

        if (!$nearestDriver) {
            $radius = 5; // Extend radius to 5 km
            $nearestDriver = $this->searchNearbyDrivers($request,$radius);
        }
        if ($nearestDriver) {
            broadcast(new TripEvent($trip));
            
            $trip->driver_id = $nearestDriver->id;
            $trip->save();
            // broadcast(new BookingEvent($nearestDriver));
            return response()->json($trip);
        } else {
            return response()->json(['message' => 'No nearby drivers found'], 404);
        }

        

      
       

        // return response()->json([ 'trip'=>$trips,'message' => 'Trip created successfully'], 201);
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
            'cartype' => 'required',

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
        $trip->cartype = $request->cartype;

        $trip->start_address = $request->start_address;
        $trip->end_address = $request->end_address;
        $trip->status = $request->status;
        $trip->driver_id = $request->driver_id;


        $trip->save();

        $trip = [
            'id' => $trip->id,
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
            'cartype' =>$request->cartype,

             
        ];

        broadcast(new TripEvent($trip));

        return response()->json(['trip'=>$trip,'message' => 'Trip updated successfully'], 200);
    }

    public function destroy($id)
    {
        $trip = Trip::findOrFail($id);

        if (!$trip) {
            return response()->json(['error' => 'Trip not found'], 404);
        }

        $trip->delete();

        return response()->json(['trip' => $trip,'message' => 'Trip deleted successfully'], 200);
    }

    private function searchNearbyDrivers($request,$radius){
        // $latitude = 27.533;
        // $longitude = 22.733;
        $latitude = $request->start_lat;
        $longitude = $request->start_lng;
        
                
        $radius = $radius;
      


        // $drivers = User::where('available', true)->whereBetween('lat', [$lower_latitude, $upper_latitude])->whereBetween('lng', [$lower_longitude, $upper_longitude])->get();
        // $nearbyDrivers = User::role('user')
        // ->where('available', true)
        // ->where('status','active')
        // ->selectRaw(
        //     'id, ( 6371 * acos( cos( radians(?) ) *
        //       cos( radians( lat ) )
        //       * cos( radians( lng ) - radians(?)
        //       ) + sin( radians(?) ) *
        //       sin( radians( lat ) ) )
        //     ) AS distance', [$latitude, $longitude, $latitude])
        // ->having('distance', '<=', $radius)
        // ->orderBy('distance')
        // ->first();
        
        $types = User::role('user')
        ->whereHas('vehicle', function ($query) {
            $query->whereJsonContains('type', 1);
        })
        ->with('vehicle')
        ->selectRaw(
            'id, ( 6371 * acos( cos( radians(?) ) *
              cos( radians( lat ) )
              * cos( radians( lng ) - radians(?)
              ) + sin( radians(?) ) *
              sin( radians( lat ) ) )
            ) AS distance', [$latitude, $longitude, $latitude])
            ->where('available', true)->where('status', 'pending')->get();
    
    $nearbyDriver = $types->first();

        if ($nearbyDriver) {
            return  $nearbyDriver;
            
        }
    }
}
