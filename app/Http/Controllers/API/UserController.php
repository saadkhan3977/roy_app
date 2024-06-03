<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Validator;
use Image;
use File;
class UserController extends BaseController
{
    public function profile_update(Request $request)
    {
        try{
			$olduser = User::where('id',Auth::user()->id)->first();
			$validator = Validator::make($request->all(),[
				'name' =>'required',
	            'address' =>'required',
	            'postal_code' =>'required',
	            'country' =>'required',
				'photo' => 'image|mimes:jpeg,png,jpg,bmp,gif,svg|max:2048',

			]);
			if($validator->fails())
			{
				return $this->sendError($validator->errors()->first());

			}
			$profile = $olduser->photo;


			if($request->hasFile('photo'))
			{
				$file = request()->file('photo');
				$fileName = md5($file->getClientOriginalName() . time()) . "Fashionstore." . $file->getClientOriginalExtension();
				$file->move('uploads/user/profiles/', $fileName);
				$profile = asset('uploads/user/profiles/'.$fileName);
			}
			$olduser->name = $request->name;
			$olduser->address = $request->address;
            $olduser->postal_code = $request->postal_code;
			$olduser->country = $request->country;
			$olduser->photo = $profile;
			$olduser->save();



			$user = User::find(Auth::user()->id);

			return response()->json(['success'=>true,'message'=>'Profile Updated Successfully','user_info'=>$user]);
		}
		catch(\Eception $e)
		{
			return $this->sendError($e->getMessage());
		}

    }
}
