<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Hash;

class ChangePasswordController extends Controller
{
    public function change_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|min:6|max:100',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json([
              'message' => 'validations fails',
              'errors' => $validator->errors()
            ],422);
        }

        $user = $request->user();
        if(Hash::check($request->old_password,$user->password)){
            $user->update([
                'password'=>Hash::make($request->password)
            ]);
            return response()->json([
              'message'=> 'Password Successfully Updated',
            ],200);
        }
        else{
            return response()->json([
                'message'=> 'Old Password Does Not Matched',
              ],400);
        }
    }
}
