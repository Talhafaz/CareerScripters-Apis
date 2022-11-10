<?php

namespace App\Http\Controllers;
//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
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Exception;

class UserController extends Controller
{
    public function Login(Request $request)
    {
        try {

            $user = User::where('email', $request->email)->first();
            if (!$user || !HASH::check($request->password, $user->password)) {
                return response()->json(['error' => 'Invalid credentials']);
            }

            return response()->json(['token' => $user->createToken('auth')->plainTextToken]);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }

    public function register(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                return response("user already exist", 400);
            } else {
                $adminRole   = Role::where('name', $request->role)->first();
                $admin       = User::create([
                    'name'     => $request->name,
                    'email'    => $request->email,
                    'password' => Hash::make($request->password)
                ]);
                $admin->roles()->attach($adminRole);

                return response()->json(["status" => "ok", "message" => "Registered Succefully"]);
            }
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }

    public function getUser(Request $request)
    {
        try {
            // return $request->user()->roles();
            $user = User::with('roles')->where('id', $request->user()->id)->first();
            return response()->json(['status' => 'ok', 'user' => $user]);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }
    public function updateUser(Request $request)
    {
        try {
            // return $request->user()->roles();
            $user = User::with('roles')->where('id', $request->user()->id)->first();
            if ($request->name) {
                $user->name = $request->name;
            }
            if ($request->picture) {
                $id = $user->id;
                $path =  'users';
                $filePath = "";
                $filename = "";
                if ($request->hasFile('picture')) {
                    $file      = $request->file('picture');
                    $extension = $file->getClientOriginalExtension();
                    $filename  = $id.'.'. $extension;
                    $file->move(public_path($path), $filename);
                }
                $user->profile_pic = $filename;
            }
            $user->save();
            return response()->json(['status' => 200,'message' => 'Profile Updated', 'user' => $user]);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }
    public function generatePassword()
    {
        $length = 10;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
