<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Hash;
class ResetPasswordController extends BaseController
{
    public function reset_password(Request $request)
    {
        $validator = Validator::make($request->all(),[
			'email' => 'required|exists:users',
            'password' => 'required|string|min:8',
			'confirm_password' => 'required|same:password',
        ]);
        if ($validator->fails())
        {
            return $this->sendError($validator->errors()->first());
        }

		$user =User::firstWhere('email',$request->email);

        if($user != null){
		 $user->update([
			 'password'=>Hash::make($request['password']),
			'email_code'=> null
		 ]);

		return response()->json(['user'=>$user,'message'=>'successfully password reset']);

        }
        else
        {
		return response()->json(['message'=>'User does not exitsts']);

        }
        
    }
}
