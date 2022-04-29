<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Critique;
use App\Models\CritiqueIndustry;
use App\Models\CritiquePresentationComment;
use App\Models\CritiqueGrammarComment;
use Illuminate\Http\Request;
use Exception;

class CritiqueController extends Controller
{
    public function createCritique(Request $request){
        try {
            $filename = "";
            if($request->hasFile('file')){
                $file      = $request->file('file');
                $filename  = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                if(!file_exists(public_path('critiques/'.$filename))){
                    $file->move(public_path('critiques'), $filename);
                }
            }
            $critique         = new Critique;
            $critique->file   = $filename;
            $critique->status = "Pending";
            $critique->save();
            $critique->users()->attach($request->user());
            return response()->json(["status"=>"ok","message"=>"Created Succesfully"]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }

    public function updateCritique(Request $request){
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
            for ($i=0; $i < count($request->industries) ; $i++) { 
                $critiqueIndustry        = new CritiqueIndustry;
                $critiqueIndustry->name  = $request->industries[$i]['name'];
                $critiqueIndustry->value = $request->industries[$i]['value'];
                $critiqueIndustry->save();
                $critique->critiqueIndustries()->attach($critiqueIndustry);
            }
            for ($i=0; $i < count($request->grammarFlow) ; $i++) { 
                $critiqueGrammarComment              = new CritiqueGrammarComment;
                $critiqueGrammarComment->title       = $request->grammarFlow[$i]['title'];
                $critiqueGrammarComment->description = $request->grammarFlow[$i]['description'];
                $critiqueGrammarComment->status      = $request->grammarFlow[$i]['status'];
                $critiqueGrammarComment->save();
                $critique->critiqueGrammarComments()->attach($critiqueGrammarComment);
            }
            for ($i=0; $i < count($request->visualPresenatation) ; $i++) { 
                $critiquePresenatationComment              = new CritiquePresentationComment;
                $critiquePresenatationComment->title       = $request->visualPresenatation[$i]['title'];
                $critiquePresenatationComment->description = $request->visualPresenatation[$i]['description'];
                $critiquePresenatationComment->status      = $request->visualPresenatation[$i]['status'];
                $critiquePresenatationComment->save();
                $critique->critiquePresentationComments()->attach($critiquePresenatationComment);
            }

            return response()->json(["status"=>"ok","message"=>"Updated Succesfully"]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }

    public function getCritiques(){
        try {
            $critiques = Critique::with(['users'])->get();
            return response()->json(['status'=>'ok','critiques'=>$critiques]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        } 
    }

    public function getCritiqueById($id){
        try {
            $critique = Critique::with(['users','critiquePresentationComments','critiqueGrammarComments','critiqueIndustries'])->where('id', $id)->first();
            return response()->json(['status'=>'ok','critique'=>$critique]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }

    public function getCritiquesByUser(Request $request){
        try {
            $critiques = User::with(['critiques' => function ($query)use($request) {
                $query->with(['critiquePresentationComments','critiqueGrammarComments','critiqueIndustries']);
            }])->where('id', $request->user()->id)->get();
            return response()->json(['status'=>'ok','critiques'=>$critiques]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }   
    }

    public function deleteCritiqueById($id){
        try {
            return response()->json(["status"=>"ok","message"=>"Updated Succesfully"]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        } 
    }
}
