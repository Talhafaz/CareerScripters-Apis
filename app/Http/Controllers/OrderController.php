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
    public function createOrder(Request $request)
    {
        try {
            $order          = new Order;
            $order->status  = "1";
            $order->price   = $request->amount;
            $order->save();

            if ($request->type == "service") {
                $service = Service::where('id', $request->id)->first();
                $order->services()->attach($service);
            } else {
                $package = Package::where('id', $request->id)->first();
                $order->packages()->attach($package);
            }
            $serviceType = ServiceType::where('id', $request->service_type_id)->first();
            $order->users()->attach($request->user());
            $order->serviceTypes()->attach($serviceType);
            return response()->json(["status" => "ok", "message" => "Created Succesfully", "order" => $order]);
        } catch (Exception $e) {
            //throw $th;
        }
    }

    public function updateOrder(Request $request)
    {
        try {
            $order         = Order::find($request->id);
            $res = array();
            if ($request->status) {
                $order->status = $request->status;
            }
            if ($request->answers) {
                $order->answers = $request->answers;
            }
            if ($request->chat) {
                $order->chat = $request->chat;
            }
            if ($request->resume_details) {
                $order->resume_details = $request->resume_details;
            }
            if ($request->file) {
                $order = Order::with(['serviceTypes', 'services', 'packages', 'users'])->where('id', $request->id)->first();
                $files = array();
                if ($order->files != null) {
                    $files = json_decode($order->files);
                }
                try {
                    $id = $request->id;
                    $path =  'files/' . $id;
                    $filePath = "";
                    $filename = "";
                    if ($request->hasFile('file')) {
                        $file      = $request->file('file');
                        $filename  = $file->getClientOriginalName();
                        $extension = $file->getClientOriginalExtension();
                        if (!file_exists(public_path($path . '/' . $filename))) {
                            $file->move(public_path($path), $filename);
                            $filePath = $path . '/' . $filename;
                        } else {
                            $fname = gmdate("His") . $filename;
                            $file->move(public_path($path), $fname);
                            $filePath = $path . '/' . $fname;
                        }
                    }
                    $file = array();
                    $file['date'] = gmdate("d-m-Y H:i:s");
                    $file['file'] = $filename;
                    $file['file_path'] = $filePath;

                    $res['file'] = $filename;
                    $res['file_path'] = $filePath;
                    if ($request->fileStatus) {
                        $file['file_status'] = $request->fileStatus;
                    } else {
                        $file['file_status'] = 'others';
                    }
                    array_push($files, $file);
                    $order->files = json_encode($files);
                } catch (Exception $e) {
                    return response()->json(["status" => "error", "message" => $e]);
                }
            }
            $order->save();
            if ($request->file) {
                return response()->json(['status' => 'ok', 'message' => "uploaded Successfully", 'data' => $res]);
            } else {
                return response()->json(['status' => 'ok', 'message' => "Updated Successfully"]);
            }
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }

    public function uploadQuestionsFile(Request $request)
    {
        try {
            $filename = "";
            if ($request->hasFile('file')) {
                $file      = $request->file('file');
                $filename  = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                if (!file_exists(public_path('questions_files/' . $filename))) {
                    $file->move(public_path('questions_files'), $filename);
                }
            }

            $order                  = Order::find($request->id);
            $order->status          = "Questionnaire Start";
            $order->questions_file  = $filename;
            $order->save();
            return response()->json(['status' => 'ok', 'message' => "Uploaded Successfully"]);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }

    public function uploadAnswersFile(Request $request)
    {
        try {
            $filename = "";
            if ($request->hasFile('file')) {
                $file      = $request->file('file');
                $filename  = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                if (!file_exists(public_path('answers_files/' . $filename))) {
                    $file->move(public_path('answers_files'), $filename);
                }
            }

            $order               = Order::find($request->id);
            $order->status       = "2";
            $order->answers_file = $filename;
            $order->answers = $request->answers;
            $order->save();
            return response()->json(['status' => 'ok', 'message' => "Uploaded Successfully"]);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }

    public function getOrders()
    {
        try {
            $orders = Order::with(['serviceTypes', 'services', 'packages', 'users'])->get();
            return response()->json(['status' => 'ok', 'orders' => $orders]);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }

    public function getOrderById($id)
    {
        try {
            $order = Order::with(['serviceTypes', 'services', 'packages', 'users'])->where('id', $id)->first();
            return response()->json(['status' => 'ok', 'order' => $order]);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }

    public function getOrdersByUser(Request $request)
    {
        try {
            $orders = User::with(['orders' => function ($query) use ($request) {
                $query->with(['serviceTypes', 'services', 'packages', 'users']);
            }])->where('id', $request->user()->id)->get();
            return response()->json(['status' => 'ok', 'order' => $orders]);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }

    public function deleteOrderById($id)
    {
        try {
        } catch (Exception $e) {
            //throw $th;
        }
    }

    public function stripe(Request $request)
    {
        try {
            $key = env("STRIPE_KEY", "sk_test_51GsRqCHBOqdNhpQrAJFlmes90OIjvVtNHlxPZNXOoeAeZlhS7BfxKEgcgGcjKFD1deYZJ7PGPybicAeEEtLnJSBb00VOmeCP2z");

            $stripe = new \Stripe\StripeClient($key);
            $result = $stripe->charges->create([
                'amount' => ($request->amount * 100),
                'currency' => $request->currency,
                'source' => $request->stoken,
                'capture' => false,
            ]);
            // error_log($result);
            if ($result->status == 'succeeded') {
                $response = $stripe->charges->capture($result->id);
                if ($response->status == 'succeeded' & ($response->captured == true || $response->captured == 'true')) {
                    return response()->json(["status" => 200, "message" => "Payment captured successfully", "trn" => $response->id]);
                }else{
                    return response()->json(["status" => 400, "message" => "Failed to capture payment"]);

                }
            }else{
                return response()->json(["status" => 400, "message" => "Failed to capture payment"]);

            }
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }
}
