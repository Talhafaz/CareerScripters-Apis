<?php

namespace App\Http\Controllers;
//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
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
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
                    $filename  = $id . '.' . $extension;
                    $file->move(public_path($path), $filename);
                }
                $user->profile_pic = $filename;
            }
            if ($request->new_password) {
                if (HASH::check($request->password, $user->password)) {
                    $user->password = Hash::make($request->new_password);
                } else {
                    return response("Password is incorrect", 400);
                }
            }
            $user->save();
            return response()->json(['status' => 200, 'message' => 'Profile Updated', 'user' => $user]);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }
    public function forgotPassword(Request $request){
        $user = User::where('email', $request->email)->first();
            if ($user) {
                $password = app(UserController::class)->generatePassword();
                $user->password = Hash::make($password);
                try {
                    $emailBody = "<html><head></head><body><p>Hello $user->name,</p>You are receiving this e-mail because you requested a password reset for your user account at Resume Scripters,</p>
                <p><strong>Username :</strong> $request->email</p>
                <p><strong>Password :</strong> $password</p>
                <br>
                <p>It's a temporary password, Please change it from your profile settings.</p></body></html>";
                    $smtpKey = env("SMTP_KEY", "xkeysib-vxvxcvbcx-FTbDsPcqU375dr9X");

                    $body = array();
                    $body['sender']['name'] = 'Resume scripters';
                    $body['sender']['email'] = 'info@resumescripters.com';
                    $body['to'][0]['email'] = $request->email;
                    $body['to'][0]['name'] = $user->name;
                    $body['subject'] = 'Resume scripters - Critique Request';
                    $body['htmlContent'] = $emailBody;
                    $response = Http::withHeaders(['api-key' => $smtpKey, 'content-type' => 'application/json'])
                        ->send('POST', 'https://api.sendinblue.com/v3/smtp/email', [
                            'body' => json_encode($body)
                        ])->json();
                        error_log(json_encode($response));
                } catch (Exception $e) {
                    return response("Error sending email",400);
                }
                $user->save();
                return response()->json(['status' => 200, 'message' => 'Password reset', 'user' => $user]);
            }
            else{
                return response("No user exist with this email", 400);
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
