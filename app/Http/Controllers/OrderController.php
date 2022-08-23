<?php

namespace App\Http\Controllers;
use Exception;
use App\Models\User;
use App\Models\Order;
use App\Models\Service;
use App\Models\Package;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function createOrder(Request $request){
        try {
            $order          = new Order;
            $order->status  = "Order Placed";
            $order->price   = $request->amount;
            $order->save();

            if($request->type == "service"){
                $service = Service::where('id', $request->id)->first();
                $order->services()->attach($service);
            }else{
                $package = Package::where('id', $request->id)->first();
                $order->packages()->attach($package);
            }
            $serviceType = ServiceType::where('id', $request->service_type_id)->first();
            $order->users()->attach($request->user());
            $order->serviceTypes()->attach($serviceType);
            return response()->json(["status"=>"ok","message"=>"Created Succesfully", "order"=>$order]);
        
    }catch (Exception $e) {
            //throw $th;
        }
    }

    public function updateOrder(Request $request){
        try {
            $order         = Order::find($request->id);
            $order->status = $request->status;
            $order->answers = $request->answers;
            $order->save();
            return response()->json(['status'=>'ok','message'=>"Uploaded Successfully"]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }

    public function uploadQuestionsFile(Request $request){
        try {
            $filename = "";
            if($request->hasFile('file')){
                $file      = $request->file('file');
                $filename  = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                if(!file_exists(public_path('questions_files/'.$filename))){
                    $file->move(public_path('questions_files'), $filename);
                }
            }
            
            $order                  = Order::find($request->id);
            $order->status          = "Questionnaire Start";
            $order->questions_file  = $filename;
            $order->save();
            return response()->json(['status'=>'ok','message'=>"Uploaded Successfully"]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
        
    }

    public function uploadAnswersFile(Request $request){
        try {
            $filename = "";
            if($request->hasFile('file')){
                $file      = $request->file('file');
                $filename  = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                if(!file_exists(public_path('answers_files/'.$filename))){
                    $file->move(public_path('answers_files'), $filename);
                }
            }
            
            $order               = Order::find($request->id);
            $order->status       = "Questionnaire Completed";
            $order->answers_file = $filename;
            $order->answers = $request->answers;
            $order->save();
            return response()->json(['status'=>'ok','message'=>"Uploaded Successfully"]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
        
    }

    public function getOrders(){
        try {
            $orders = Order::with(['serviceTypes','services','packages','users'])->get();
            return response()->json(['status'=>'ok','orders'=>$orders]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }

    public function getOrderById($id){
        try {
            $order = Order::with(['serviceTypes','services','packages','users'])->where('id', $id)->first();
            return response()->json(['status'=>'ok','order'=>$order]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }

    public function getOrdersByUser(Request $request){
        try {
            $orders = User::with(['orders' => function ($query)use($request) {
                $query->with(['serviceTypes','services','packages','users']);
            }])->where('id', $request->user()->id)->get();
            return response()->json(['status'=>'ok','order'=>$orders]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }

    public function deleteOrderById($id){
        try {
            
            
        } catch (Exception $e) {
            //throw $th;
        } 
    }
}
