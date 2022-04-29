<?php

namespace App\Http\Controllers;
use App\Models\Service;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function createService(Request $request){
        try {

            $serviceType          = ServiceType::where('name', $request->service_type)->first();
            $service              = new Service;
            $service->title       = $request->title;
            $service->description = $request->description;
            $service->price       = $request->price;
            $service->save();
            $service->serviceTypes()->attach($serviceType);

            return response()->json(["status"=>"ok","message"=>"Created Succesfully"]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }

    }

    public function updateService(Request $request){
        try {
            $serviceType          = ServiceType::where('name', $request->service_type)->first();
            $service              = Service::find($request->id);
            $service->title       = $request->title;
            $service->description = $request->description;
            $service->price       = $request->price;
            $service->save();
            $service->serviceTypes()->detach();
            $service->serviceTypes()->attach($serviceType);

            return response()->json(["status"=>"ok","message"=>"Updated Succesfully"]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }
    
    public function getServices(){
        try {
            $services = Service::with('serviceTypes')->get();
            return response()->json(['status'=>'ok','services'=>$services]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }

    public function getServiceById($id){
        try {
            $service = Service::with('serviceTypes')->where('id', $id)->first();
            return response()->json(['status'=>'ok','service'=>$service]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }

    public function getServicesByType($type){
        try {
            $services = ServiceType::with('services')->where('name', $type)->get();
            return response()->json(['status'=>'ok','service'=>$services]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }

    public function deleteServiceById($id){
        try {
            $service = Service::find($id);
            $service->serviceTypes()->detach();
            $service->delete();
            return response()->json(['status'=>'ok','message'=>'Succesfully Deleted']);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }
}
