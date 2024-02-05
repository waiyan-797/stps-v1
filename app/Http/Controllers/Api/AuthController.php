<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserImage;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|unique:users,phone|max:255',
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'email' => 'nullable|email|unique:users,email|max:255',
            'birth_date' => 'nullable',
            'address' => 'nullable|string|max:255',
            'nrc_no' => 'nullable|string|unique:users,nrc_no|max:255',
            'driving_license' => 'nullable|string|unique:users,driving_license|max:255',
            'vehicle_model' => 'nullable|string|max:255',
            'vehicle_plate_no' => 'nullable|string|unique:vehicles,vehicle_plate_no|max:255',
            // 'front_nrc_image' => 'nullable|image',
            // 'back_nrc_image' => 'nullable|image',
            // 'front_license_image' => 'nullable|image',
            // 'back_license_image' => 'nullable|image',
            // 'vehicle_image' => 'nullable|image'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->all()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(10)
        ])->assignRole('user');
        $user->driver_id = sprintf('%04d', $user->id - 1);
        $user->fill($request->only(['birth_date', 'address', 'nrc_no', 'driving_license']));

        $user->save();

        $vehicle = new Vehicle();
        $vehicle->user_id = $user->id;

        if ($request->has('vehicle_plate_no')) {
            $vehicle->vehicle_plate_no = $request->vehicle_plate_no;
        }
        if ($request->has('vehicle_model')) {
            $vehicle->vehicle_model = $request->vehicle_model;
        }
        // if ($request->hasFile('vehicle_image')) {
        //     $vehicleImage = $request->file('vehicle_image');
        //     $vehicleImageName = time() . '.' . $vehicleImage->getClientOriginalExtension();
        //     $vehicleImage->storeAs('uploads/images/vehicles', $vehicleImageName);
        //     $vehicle->vehicle_image_url = $vehicleImageName;
        // }
        $vehicle->save();

        $userImage = new UserImage();
        $userImage->user_id = $user->id;

        // // upload and save front NRC image
        // if ($request->hasFile('front_nrc_image')) {
        //     $frontNrcImage = $request->file('front_nrc_image');
        //     $frontNrcImageName = time() . '.' . $frontNrcImage->getClientOriginalExtension();
        //     $frontNrcImage->storeAs('uploads/images/front_nrcs', $frontNrcImageName);
        //     $userImage->front_nrc_image = $frontNrcImageName;
        // }

        // // upload and save back NRC image
        // if ($request->hasFile('back_nrc_image')) {
        //     $backNrcImage = $request->file('back_nrc_image');
        //     $backNrcImageName = time() . '.' . $backNrcImage->getClientOriginalExtension();
        //     $backNrcImage->storeAs('uploads/images/back_nrcs', $backNrcImageName);
        //     $userImage->back_nrc_image = $backNrcImageName;
        // }

        // // upload and save front license image
        // if ($request->hasFile('front_license_image')) {
        //     $frontLicenseImage = $request->file('front_license_image');
        //     $frontLicenseImageName = time() . '.' . $frontLicenseImage->getClientOriginalExtension();
        //     $frontLicenseImage->storeAs('uploads/images/front_licenses', $frontLicenseImageName);
        //     $userImage->front_license_image = $frontLicenseImageName;
        // }

        // // upload and save back license image
        // if ($request->hasFile('back_license_image')) {
        //     $backLicenseImage = $request->file('back_license_image');
        //     $backLicenseImageName = time() . '.' . $backLicenseImage->getClientOriginalExtension();
        //     $backLicenseImage->storeAs('uploads/images/back_licenses', $backLicenseImageName);
        //     $userImage->back_license_image = $backLicenseImageName;
        // }

        // save user images to database
        $userImage->save();

        $token = $user->createToken($user->email . '_' . now(), [$user->roles->first()->name]);

        return response(['token' => $token], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'phone' => 'required|string|exists:users,phone|max:255',
                'password' => 'required|string|min:8',
            ]
        );
        if ($validator->fails()) {
            return response(['message' => $validator->errors()->all()], 422);
        }
        $user = User::where('phone', $request->phone)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken($user->email . '_' . now(), [$user->roles->first()->name]);
                return response()->json(['token' =>  $token, 'status' => $user->status], 200);
            } else {
                $response = ["message" => ["Password mismatch"]];
                return response($response, 422);
            }
        } else {
            $response = ["message" => ['User does not exist']];
            return response($response, 422);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        $response = ['message' => ['You have been successfully logged out!']];
        return response($response, 200);
    }
}
