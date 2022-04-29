<?php

namespace App\Http\Controllers;
use App\Models\Service;
use App\Models\ServiceType;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function createPackage(Request $request){
        try {

            $serviceType          = ServiceType::where('name', $request->service_type)->first();
            $package              = new Package;
            $package->title       = $request->title;
            $package->description = $request->description;
            $package->price       = $request->price;
            $package->save();

            for ($i=0; $i < count($request->services); $i++) { 
                $service = Service::where('id', $request->services[$i])->first();
                $package->services()->attach($service);
            }

            $package->serviceTypes()->attach($serviceType);

            return response()->json(["status"=>"ok","message"=>"Created Succesfully"]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }

    public function updatePackage(Request $request){
        try {

            $serviceType          = ServiceType::where('name', $request->service_type)->first();
            $package              = Package::find($request->id);
            $package->title       = $request->title;
            $package->description = $request->description;
            $package->price       = $request->price;
            $package->save();
            $package->serviceTypes()->detach();
            $package->services()->detach();
            for ($i=0; $i < count($request->services); $i++) { 
                $service = Service::where('id', $request->services[$i])->first();
                $package->services()->attach($service);
            }
            $package->serviceTypes()->attach($serviceType);
            return response()->json(["status"=>"ok","message"=>"Updated Succesfully"]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }
    
    public function getPackages(){
        try {
            $packages = Package::with(['serviceTypes','services'])->get();
            return response()->json(['status'=>'ok','packages'=>$packages]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }

    public function getPackageById($id){
        try {
            $package = Package::with(['serviceTypes','services'])->where('id', $id)->first();
            return response()->json(['status'=>'ok','package'=>$package]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }

    public function getPackagesByType($type){
        try {
            $packages = Package::with(['serviceTypes' => function ($query)use($type) {
                $query->where('name', $type);
            },'services'])->get();
            return response()->json(['status'=>'ok','packages'=>$packages]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }

    public function deletePackageById($id){
        try {
            $package = Package::find($id);
            $package->serviceTypes()->detach();
            $package->services()->detach();
            $package->delete();
            return response()->json(['status'=>'ok','message'=>'Succesfully Deleted']);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }
}
