<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
	public function noauth()
	{
	 	return $this->sendError('session destroyed , Login to continue!');
	}
    public function register(Request $request)
    {
		try{
				
			
			$validator = Validator::make($request->all(), [
				'name' => 'required',
				'email' => 'required|email|unique:users,email',
				'password' => 'required',
				'address' => 'required',
				'country' => 'required',
				'postal_code' => 'required',
				'contact' => 'required',
				'c_password' => 'required|same:password',
			]);

			if($validator->fails()){
				return response()->json(['success'=>false,'message'=> $validator->errors()->first()],500);
			}
		
			$input = $request->all();
			$input['password'] = bcrypt($input['password']);
			$user = User::create($input);
			$success['token'] =  $user->createToken('MyApp')->plainTextToken;
			$success['use_info'] =  $user;

			return $this->sendResponse($success, 'User register successfully.');
		} catch (\Exception $e) {
			return $this->sendError('Validation Error.', $e->getMessage());
			//return $e->getMessage();
		}
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['use_info'] =  $user;

            return $this->sendResponse($success, 'User login successfully.');
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }
}
