<?php

namespace App\Http\Controllers\Api;

use App\Events\DriverEvent;
use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\User;
use App\Models\UserImage;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    

    public function profile()
    {
        $user = Auth::user();
        $userImage = $user->userImage;
        $vehicle = $user->vehicle;
        $transaction = $user->transactions;
        $trip = $user->trip;
        $notification = $user->notifications;

        return response()->json(['user' => $user, 'success' => true], 200);
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8| max:255',
            'email' => 'nullable|email|max:255',
            'birth_date' => 'nullable',
            'address' => 'nullable|string|max:255',
            'nrc_no' => 'nullable|string|max:255',
            'driving_license' => 'nullable|string|max:255',
            'vehicle_model' => 'nullable|string|max:255',
            'vehicle_plate_no' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image',
            'front_nrc_image' => 'nullable|image',
            'back_nrc_image' => 'nullable|image',
            'front_license_image' => 'nullable|image',
            'back_license_image' => 'nullable|image',
            'vehicle_image' => 'nullable|image'
        ]);

        $user = Auth::user();
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 422);
        }
        $user->fill($request->only(['name', 'phone', 'email', 'birth_date', 'address', 'nrc_no', 'driving_license']));

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        if ($user->userImage) {
            $userImage = UserImage::where('user_id', $user->id)->first();
        } else {
            $userImage = new UserImage();
            $userImage->user_id = $user->id;
        }
        // update profile image
        if ($request->hasFile('profile_image')) {
            if (Storage::exists('uploads/images/profiles/' . $userImage->profile_image)) {
                Storage::delete('uploads/images/profiles/' . $userImage->profile_image); //delete old image
            }
            $profileImage = $request->file('profile_image');
            $profileImageName = time() . '_' . $user->nrc_no . '.' . $profileImage->getClientOriginalExtension();
            $profileImage->storeAs('uploads/images/profiles', $profileImageName);
            $userImage->profile_image = $profileImageName;
            $userImage->save();
        }

        // // update front NRC image
        if ($request->hasFile('front_nrc_image')) {
            if (Storage::exists('uploads/images/front_nrcs/' . $userImage->front_nrc_image)) {
                Storage::delete('uploads/images/front_nrcs/' . $userImage->front_nrc_image); //delete old image
            }
            $frontNrcImage = $request->file('front_nrc_image');
            $frontNrcImageName = time() . '.' . $frontNrcImage->getClientOriginalExtension();
            $frontNrcImage->storeAs('uploads/images/front_nrcs/', $frontNrcImageName);
            $userImage->front_nrc_image = $frontNrcImageName;
            $userImage->save();
        }

        // // update back NRC image
        if ($request->hasFile('back_nrc_image')) {
            if (Storage::exists('uploads/images/back_nrcs/' . $userImage->back_nrc_image)) {
                Storage::delete('uploads/images/back_nrcs/' . $userImage->back_nrc_image); //delete old image
            }
            $backNrcImage = $request->file('back_nrc_image');
            $backNrcImageName = time() . '.' . $backNrcImage->getClientOriginalExtension();
            $backNrcImage->storeAs('uploads/images/back_nrcs/', $backNrcImageName);
            $userImage->back_nrc_image = $backNrcImageName;
            $userImage->save();
        }

        // // update front license image
        if ($request->hasFile('front_license_image')) {
            if (Storage::exists('uploads/images/front_licenses/' . $userImage->front_license_image)) {
                Storage::delete('uploads/images/front_licenses/' . $userImage->front_license_image); //delete old image
            }
            $backNrcImage = $request->file('front_license_image');
            $backNrcImageName = time() . '.' . $backNrcImage->getClientOriginalExtension();
            $backNrcImage->storeAs('uploads/images/front_licenses/', $backNrcImageName);
            $userImage->front_license_image = $backNrcImageName;
            $userImage->save();
        }
        // // update back license image
        if ($request->hasFile('back_license_image')) {
            if (Storage::exists('uploads/images/back_licenses/' . $userImage->back_license_image)) {
                Storage::delete('uploads/images/back_licenses/' . $userImage->back_license_image); //delete old image
            }
            $backLicenseImage = $request->file('back_license_image');
            $backLicenseImageName = time() . '.' . $backLicenseImage->getClientOriginalExtension();
            $backLicenseImage->storeAs('uploads/images/back_licenses/', $backLicenseImageName);
            $userImage->back_license_image = $backLicenseImageName;
            $userImage->save();
        }

        // Vehicle Data update
        $validator = Validator::make($request->all(), [
            'vehicle_plate_no' => 'nullable|string|max:255',
            'vehicle_model' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 422);
        }

        if ($user->vehicle) {
            $vehicle = Vehicle::where('user_id', $user->id)->first();
        } else {
            $vehicle = new Vehicle();
            $vehicle->user_id = $user->id;
        }

        if ($request->has('vehicle_plate_no')) {
            $vehicle->vehicle_plate_no = $request->vehicle_plate_no;
        }
        if ($request->has('vehicle_model')) {
            $vehicle->vehicle_model = $request->vehicle_model;
        }
        if ($request->hasFile('vehicle_image')) {

            $oldImage = $vehicle->vehicle_image_url; //get old image by ID
            Storage::delete('uploads/images/vehicles/' . $oldImage); //delete old image

            $vehicleImage = $request->file('vehicle_image');
            $vehicleImageName = time() . '.' . $vehicleImage->getClientOriginalExtension();
            $vehicleImage->storeAs('uploads/images/vehicles', $vehicleImageName);
            $vehicle->vehicle_image_url = $vehicleImageName;
        }
        $vehicle->save();
        return response()->json(['user' => $user, 'status' => 'User updated successfully', 'success' => true], 200);
    }

    public function destroy(User $user)
    {
        if ($user->has('userImage')) {
            $userImage = $user->userImage;
            if (isset($userImage->profile_image)) {
                Storage::delete('uploads/images/profiles/' . $userImage->profile_image);
            }
            if (isset($userImage->front_nrc_image)) {
                Storage::delete('uploads/images/front_nrcs/' . $userImage->front_nrc_image);
            }
            if (isset($userImage->back_nrc_image)) {
                Storage::delete('uploads/images/back_nrcs/' . $userImage->back_nrc_image);
            }
            if (isset($userImage->front_license_image)) {
                Storage::delete('uploads/images/front_licenses/' . $userImage->front_license_image);
            }
            if (isset($userImage->back_license_image)) {
                Storage::delete('uploads/images/back_licenses/' . $userImage->back_license_image);
            }
        }

        if (isset($user->vehicle)) {
            $vehicle = Vehicle::find($user->vehicle->id);
            Storage::delete('uploads/images/vehicles/' . $vehicle->vehicle_image_url);
            $vehicle->delete();
        }
        $user->tokens()->delete();
        $user->delete();
        return response()->json(['status' => 'User deleted successfully', 'success' => true], 200);
    }

    public function search(Request $request)
    {
        $key = $request->input('key');

        $users = User::role('user')->where('name', 'LIKE', "%$key%")
            ->orWhere('email', 'LIKE', "%$key%")
            ->orWhere('phone', 'LIKE', "%$key%")
            ->orWhere('driver_id', '=', $key)
            ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function userTrip()
    {
        $user = Auth::user();
        $trips = Trip::latest()->where('driver_id', $user->id)->get();
        return response()->json($trips, 200);
    }

    public function disable($user_id){
        $user = User::find($user_id);
        $user->status = 'pending';
        $user->update();
        return response()->json(['status' => 'User deleted successfully', 'success' => true], 200);


    }


    public function cusupdate(Request $request)
    {

        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255|unique:users,phone,'.$user->id,
            'password' => 'nullable|string|min:8| max:255',
            'email' => 'nullable|email|max:255',
            'birth_date' => 'nullable',
            'address' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image',
            
        ]);

       
        if ($validator->fails()) {
            return response(['message' => $validator->errors()], 422);
        }
        $user->fill($request->only(['name', 'phone', 'email', 'birth_date', 'address']));

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        
        $user->save();

        if ($user->userImage) {
            $userImage = UserImage::where('user_id', $user->id)->first();
        } else {
            $userImage = new UserImage();
            $userImage->user_id = $user->id;
        }
        // update profile image
        if ($request->hasFile('profile_image')) {
            if (Storage::exists('uploads/images/profiles/' . $userImage->profile_image)) {
                Storage::delete('uploads/images/profiles/' . $userImage->profile_image); //delete old image
            }
            $profileImage = $request->file('profile_image');
            $profileImageName = time() . '_' . $user->nrc_no . '.' . $profileImage->getClientOriginalExtension();
            $profileImage->storeAs('uploads/images/profiles', $profileImageName);
            $userImage->profile_image = $profileImageName;
            $userImage->save();
        }

       
        return response()->json(['user' => $user, 'status' => 'User updated successfully', 'success' => true], 200);
    }


    
}
