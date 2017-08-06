<?php

namespace Stmik\Http\Controllers;

use Illuminate\Http\Request;

use Stmik\Http\Requests;
use Validator;
use Stmik\User;

class ApiAuthController extends Controller
{
    /**
     * User Login Authentication
     * POST /api/users/login
     *
     * @param string  $email               User email
     * @param string  $password               User password
     * @return Response
     **/
    public function user_login(Request $request){
	
        $validator = Validator::make($request->all(),[
            "name" => "required",
            "password" => "required",
        ]);
        if($validator->fails()){
            return response()->json(["errors" => $validator->errors()->all(),"message"=>"username dan password tidak boleh kosong !"], 422);
        }else{
            $email = $request->input('name');
            $password = $request->input('password');
			//return response()->json($email);
            $user = User::where(array("name"=>$email))->first();

            if($user == null){
                return response()->json([
                    "status" => "error",
                    "message" => "Username atau Password Salah Check kembali."
                ], 403);
            } else {
                if(!password_verify($password, $user->password)){
                    return response()->json([
                        "status" => "error",
                        "message" => "Password Anda Salah"
                    ], 403);
                } else {
                    $token_string = md5(time().$user->name.$user->email.md5($request->header('User-Agent')));

                    if($user->remember_token == null || $user->remember_token == ""){
                        $user->remember_token = $token_string;
                        $user->save();
                    }

                    return response()->json([
                        "status" => true,
                        "message" => "Login Success",
                        "token" => $user->remember_token,
                        "user" => [
                            "id" => $user->id,
                            "name" => $user->name,
                            "email" => $user->email,
                        ]
                    ], 200);
                }
            }
        }
    }

    /**
     * User Logout
     * POST /api/users/logout
     *
     * @param integer  $email               Id User
     * @return Response
     **/
    public function user_logout(Request $request){
        $id = $request->input("id");
        $user = User::find($id);
        $log = "";
        if($user!=null){
            $log = $user->update([
                    "api_token" => '',
                    "token_expired" => ''
                ]);
        }

        if($log){
            return response()->json([
                "status" => "success",
                "message" => "Log Out Success",
            ], 200);
        }else{
            return response()->json([
                "status" => "error",
                "message" => "log out failed!"
            ],403);
        }
    }
}
