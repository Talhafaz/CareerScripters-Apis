<?php

namespace App\Http\Controllers;

use App\Models\Sample;
use Illuminate\Http\Request;
use Exception;

class SamplesController extends Controller
{
    public function createSample(Request $request)
    {
        try {
            $images = array();
            if ($files = $request->file('files')) {
                foreach ($files as $file) {
                    $filename  = $file->getClientOriginalName();
                    array_push($images, $filename);
                    $file->move(public_path('sample_files'), $filename);
                }
                error_log(json_encode($images));
            }
            $sample              = new Sample();
            $sample->name       = $request->name;
            $sample->category = $request->category;
            $sample->samples         = json_encode($images);

            $sample->save();


            return response()->json(["status" => "ok", "message" => "Created Succesfully"]);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }

    public function updateSample(Request $request){
        try{
            if($request->id){
                $sample = Sample::find($request->id);
                $sample->name       = $request->name;
                $sample->category = $request->category;

                $sample->save();


            return response()->json(["status" => "ok", "message" => "Update Succesfully"]);
            }
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }
    public function getSample(){
        try {
            $samples = Sample::all();
            return response()->json(['status'=>'ok','samples'=>$samples]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }

    public function getSampleById($id){
        try {
            $sample = Sample::where('id', $id)->first();
            return response()->json(['status'=>'ok','sample'=>$sample]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }
    public function deleteSampleById($id){
        try {
            $sample = Sample::find($id);
            $sample->delete();
            return response()->json(['status'=>'ok','message'=>'Succesfully Deleted']);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }
}
