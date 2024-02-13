<?php

namespace App\Http\Controllers\Api;

use App\Events\BookingEvent;
use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;

class DriverSearchController extends Controller
{
    public function searcTripnearhDriver(){
        $trip = Trip::find(3);
        

       

        $radius = 2; // Initial radius
        $nearestDriver = $this->searchNearbyDrivers($trip->cartype,$radius);


        if (!$nearestDriver) {
            $radius = 3; // Extend radius to 3 km
            $nearestDriver = $this->searchNearbyDrivers($trip->cartype,$radius);
        }

        if ($nearestDriver) {
            $trip->driver_id = $nearestDriver->id;
            $trip->save();
            // broadcast(new BookingEvent($nearestDriver));
            return response()->json($nearestDriver);
        } else {
            return response()->json(['message' => 'No nearby drivers found'], 404);
        }

           
            return response()->json($trip);

    
    }


    private function searchNearbyDrivers($triptype,$radius){
        $latitude = 27.533;
        $longitude = 22.733;
                
        $radius = $radius;
       

        // $drivers = User::where('available', true)->whereBetween('lat', [$lower_latitude, $upper_latitude])->whereBetween('lng', [$lower_longitude, $upper_longitude])->get();
        // $nearbyDrivers = User::where('available', true)
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
        
        // $vehicles = Vehicle::whereRaw("JSON_CONTAINS(type, '1')")->get();
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

    public function searchTrip($id){

        $trips = Trip::where('driver_id',$id)->where('status','pending')->get();

        return response()->json($trips);
    }
}

