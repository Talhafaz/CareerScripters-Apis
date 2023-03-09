<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Exception;

class CategoryController extends Controller
{
    public function createCategory(Request $request)
    {
        try {
            $exist = Category::where('name', $request->name)->first();
            if ($exist) {
                return response("Category already exist", 400);
            } else {
                $category              = new Category();
                $category->name       = $request->name;

                $category->save();
                return response()->json(["status" => "ok", "message" => "Created Succesfully"]);
            }
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }

    public function updateCategory(Request $request)
    {
        try {
            if ($request->id) {
                $category = Category::find($request->id);
                if ($request->name) {
                    $category->name       = $request->name;
                }
                $category->save();
                return response()->json(["status" => "ok", "message" => "Update Succesfully"]);
            }
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }
    public function getCategory()
    {
        try {
            $categorys = Category::all();
            return response()->json(['status' => 'ok', 'categorys' => $categorys]);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }

    public function getCategoryById($id)
    {
        try {
            $category = Category::where('id', $id)->first();
            return response()->json(['status' => 'ok', 'category' => $category]);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }
    public function deleteCategoryById($id)
    {
        try {
            $category = Category::find($id);
            $category->delete();
            return response()->json(['status' => 'ok', 'message' => 'Succesfully Deleted']);
        } catch (Exception $e) {
            return response()->json(["status" => "error", "message" => $e]);
        }
    }
}
