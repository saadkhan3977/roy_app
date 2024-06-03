<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\ResetCodePassword;
use App\Models\User;

class CodeCheckController extends Controller
{
    public function code_check(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'code' => 'required|string|exists:reset_code_passwords',
            'password' => 'required|string|min:6|same:confirm_password',
        ]);

        if($validator->fails())
        {
            return response()->json(['success'=> false,'message'=>$validator->errors()->first()]);
        }
        // find the code
        $passwordReset = ResetCodePassword::firstWhere('code', $request->code);

        // check if it does not expired: the time is one hour
        if ($passwordReset->created_at > now()->addHour()) {
            $passwordReset->delete();
            return response(['message' => trans('passwords.code_is_expire')], 422);
        }

        // find user's email
        $user = User::firstWhere('email', $passwordReset->email);

        // update user password
        $user->update($request->only('password'));

        // delete current code
        ResetCodePassword::where(['email'=>$passwordReset->email,'code'=> $passwordReset->code])->delete();

        return response(['message' =>'password has been successfully reset'], 200);
    }
}
