<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Critique;
use App\Models\CritiqueIndustry;
use App\Models\CritiquePresentationComment;
use App\Models\CritiqueGrammarComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Exception;
use PhpParser\Node\Stmt\TryCatch;

class CritiqueController extends Controller
{
    public function createCritique(Request $request)
    {
        try {
            $filename = "";
            if ($request->email) {
                $user = User::where('email', $request->email)->first();
                if ($user) {
                    error_log($user);
                    if ($request->hasFile('file')) {
                        $file      = $request->file('file');
                        $filename  = $file->getClientOriginalName();
                        $extension = $file->getClientOriginalExtension();
                        if (!file_exists(public_path('critiques/' . $filename))) {
                            $file->move(public_path('critiques'), $filename);
                        }
                    }
                    $critique         = new Critique;
                    $critique->file   = $filename;
                    $critique->status = "Pending";
                    $critique->save();
                    error_log($critique);
                    $critique->users()->attach($user);

                    return response()->json(["status" => "ok", "message" => "Created Succesfully"]);
                } else {
                    $email = $request->email;
                    $password = app(UserController::class)->generatePassword();
                    $name = implode('@', explode('@', $email, -1));
                    $req = new Request();
                    $req->email = $email;
                    $req->password = $password;
                    $req->name = $name;
                    $req->role = 'User';
                    $response = app(UserController::class)->register($req);
                    if ($response->original['status'] == 'ok') {
                        try {
                            $user = User::where('email', $email)->first();
                            error_log($user);

                            if ($user) {
                                if ($request->hasFile('file')) {
                                    $file      = $request->file('file');
                                    $filename  = $file->getClientOriginalName();
                                    $extension = $file->getClientOriginalExtension();
                                    if (!file_exists(public_path('critiques/' . $filename))) {
                                        $file->move(public_path('critiques'), $filename);
                                    }
                                }
                                $critique         = new Critique;
                                $critique->file   = $filename;
                                $critique->status = "Pending";
                                $critique->save();
                                error_log($critique);
                                $critique->users()->attach($user);
            
                            }
                        } catch (Exception $e) {
                            return response()->json(["status" => "error", "message" => $e]);
                        }
                        try {
                            $emailBody = "<html><head></head><body><p>Hello $name,</p>We've reveived request for resume critique,</p>
                        <p>You check status of critique on user dashboard.</p>
                        <p><strong>Username :</strong> $email</p>
                        <p><strong>Password :</strong> $password</p></body></html>";
                            error_log($email);
                            $smtpKey = env("SMTP_KEY", "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");

                            $body = array();
                            $body['sender']['name'] = 'Resume scripters';
                            $body['sender']['email'] = 'info@resumescripters.com';
                            $body['to'][0]['email'] = $email;
                            $body['to'][0]['name'] = $name;
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
                    }
                    return response()->json(["status" => "ok", "message" => "Created Succesfully"]);
                }
            }
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }

    public function updateCritique(Request $request)
    {
        try {
            $critique                         = Critique::find($request->id);
            $critique->brevity                = $request->brevityValue;
            $critique->brevity_description    = $request->brevityDescription;
            $critique->impact                 = $request->impactValue;
            $critique->impact_description     = $request->impactDescription;
            $critique->depth                  = $request->depthValue;
            $critique->depth_description      = $request->depthDescription;
            $critique->pages                  = $request->noOfPages;
            $critique->pages_description      = $request->pagesDescription;
            $critique->word_count             = $request->wordCount;
            $critique->word_count_description = $request->wordDescription;
            $critique->file_size              = $request->fileSize;
            $critique->file_size_description  = $request->fileDescription;
            $critique->mail                   = $request->email;
            $critique->phone                  = $request->phone;
            $critique->linkedin               = $request->linkedin;
            $critique->address                = $request->address;
            $critique->status                 = "Done";
            $critique->save();
            for ($i = 0; $i < count($request->industries); $i++) {
                $critiqueIndustry        = new CritiqueIndustry;
                $critiqueIndustry->name  = $request->industries[$i]['name'];
                $critiqueIndustry->value = $request->industries[$i]['value'];
                $critiqueIndustry->save();
                $critique->critiqueIndustries()->attach($critiqueIndustry);
            }
            for ($i = 0; $i < count($request->grammarFlow); $i++) {
                $critiqueGrammarComment              = new CritiqueGrammarComment;
                $critiqueGrammarComment->title       = $request->grammarFlow[$i]['title'];
                $critiqueGrammarComment->description = $request->grammarFlow[$i]['description'];
                $critiqueGrammarComment->status      = $request->grammarFlow[$i]['status'];
                $critiqueGrammarComment->save();
                $critique->critiqueGrammarComments()->attach($critiqueGrammarComment);
            }
            for ($i = 0; $i < count($request->visualPresenatation); $i++) {
                $critiquePresenatationComment              = new CritiquePresentationComment;
                $critiquePresenatationComment->title       = $request->visualPresenatation[$i]['title'];
                $critiquePresenatationComment->description = $request->visualPresenatation[$i]['description'];
                $critiquePresenatationComment->status      = $request->visualPresenatation[$i]['status'];
                $critiquePresenatationComment->save();
                $critique->critiquePresentationComments()->attach($critiquePresenatationComment);
            }

            return response()->json(["status" => "ok", "message" => "Updated Succesfully"]);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }

    public function getCritiques()
    {
        try {
            $critiques = Critique::with(['users'])->get();
            return response()->json(['status' => 'ok', 'critiques' => $critiques]);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }

    public function getCritiqueById($id)
    {
        try {
            $critique = Critique::with(['users', 'critiquePresentationComments', 'critiqueGrammarComments', 'critiqueIndustries'])->where('id', $id)->first();
            return response()->json(['status' => 'ok', 'critique' => $critique]);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }

    public function getCritiquesByUser(Request $request)
    {
        try {
            $critiques = User::with(['critiques' => function ($query) use ($request) {
                $query->with(['critiquePresentationComments', 'critiqueGrammarComments', 'critiqueIndustries']);
            }])->where('id', $request->user()->id)->get();
            return response()->json(['status' => 'ok', 'critiques' => $critiques]);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }

    public function deleteCritiqueById($id)
    {
        try {
            return response()->json(["status" => "ok", "message" => "Updated Succesfully"]);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }
}
