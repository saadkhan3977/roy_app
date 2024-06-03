<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;

use Illuminate\Http\Request;
use App\Models\ResetCodePassword;
use Mail;
use App\Mail\SendCodeResetPassword;
use Validator;
use App\Models\User;

class ForgotPasswordController extends BaseController
{
    public function forgot_Password(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
        ]);
        if ($validator->fails()) 
        {    
            return $this->sendError($validator->errors()->first());
        } 
		
		$user = User::firstWhere('email',$request->email);
		if($user != null)
		{
			$data['code'] = mt_rand(9000, 9999);
			$data['email'] = $request->email;
			$user->update(['email_code'=>$data['code'] ]);
		    Mail::to($user->email)->send(new SendCodeResetPassword($data['code']));	
			$success = [$data];
	        return $this->sendResponse($success, trans('passwords.sent'));
		}else{
		 return $this->sendError('User does not exitsts');

		}

        }
        catch(\Exception $e)
        {
            return response(['success'=>false,'message' => $e->getMessage()], 500);
        }
    }
}
