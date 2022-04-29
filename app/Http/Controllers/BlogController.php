<?php

namespace App\Http\Controllers;
use App\Models\Blog;
use Illuminate\Http\Request;
use Exception;
class BlogController extends Controller
{
    public function createBlog(Request $request){
        try {
            $filename = "";
            if($request->hasFile('file')){
                $file      = $request->file('file');
                $filename  = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                if(!file_exists(public_path('blogs_imgs/'.$filename))){
                    $file->move(public_path('blogs_imgs'), $filename);
                }
            }
            $blog              = new Blog;
            $blog->title       = $request->title;
            $blog->description = $request->description;
            $blog->img         = $filename;
            $blog->save();

            return response()->json(["status"=>"ok","message"=>"Created Succesfully"]);

        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }

    public function updateBlog(Request $request){
        try {
            $filename = "";
            if($request->hasFile('file')){
                $file      = $request->file('file');
                $filename  = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                if(!file_exists(public_path('blogs_imgs/'.$filename))){
                    $file->move(public_path('blogs_imgs'), $filename);
                }
            }
            $blog              = Blog::find($request->id);
            $blog->title       = $request->title;
            $blog->description = $request->description;
            if($filename!=""){
                $blog->img = $filename;
            }
            $blog->save();

            return response()->json(["status"=>"ok","message"=>"Updated Succesfully"]);

        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }
    
    public function getBlogs(){
        try {
            $blogs = Blog::all();
            return response()->json(['status'=>'ok','blogs'=>$blogs]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }

    public function getBlogById($id){
        try {
            $blog = Blog::where('id', $id)->first();
            return response()->json(['status'=>'ok','blog'=>$blog]);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }

    public function deleteBlogById($id){
        try {
            $blog = Blog::find($id);
            $blog->delete();
            return response()->json(['status'=>'ok','message'=>'Succesfully Deleted']);
        } catch (Exception $e) {
            return response()->json(["status"=>"error","message"=>$e]);
        }
    }
    
}
