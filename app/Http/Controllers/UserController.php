<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserController extends Controller
{
    public function Login(Request $request){
        try {
            
            $user = User::where('email',$request->email)->first();
            if(!$user || !HASH::check($request->password,$user->password)){
                return response()->json(['error'=>'Invalid credentials']);
            }

            return response()->json(['token'=>$user->createToken('auth')->plainTextToken]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
        
    }

    public function register(Request $request){
        try {
            $user = User::where('email',$request->email)->first();
            if($user){
                return response("user already exist",400);
            }
            else{
            $adminRole   = Role::where('name', $request->role)->first();
            $admin       = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password)
            ]);
            $admin->roles()->attach($adminRole);

            return response()->json(["status"=>"ok","message"=>"Registered Succefully"]);}
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
        
    }

    public function getUser(Request $request){
        try {
            // return $request->user()->roles();
            $user = User::with('roles')->where('id', $request->user()->id)->first();
            return response()->json(['status'=>'ok','user'=>$user]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }
}
