<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use App\Http\Transformers\Result;
use App\Models\{User,Post}; 
use Illuminate\Support\Facades\DB;
use Storage;
use Carbon\Carbon;
class PostController extends Controller
{
    public function add_post(Request $request){
        try {
            $user['login'] = auth()->user();
            $id_user = $user['login']->id;     
           // dd($id_user);
            $role = $user['login']->role;    
            $res = [];   
            
            $validator = Validator::make($request->all(), [                
                'slug' => 'unique:posts',
                'image' => 'image:jpeg,png,jpg,gif,svg|max:2048'
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
            
            $image = $request->file('image');
            if($image){
                $uploadFolder = 'post';
                $image = $request->file('image');
                $image_uploaded_path = $image->store($uploadFolder, 'public');
                $uploadedImageResponse = array(
                    "image_name" => basename($image_uploaded_path),
                    "image_url" => Storage::disk('public')->url($image_uploaded_path),
                    "mime" => $image->getClientMimeType()
                );
                $image_url = Storage::disk('public')->url($image_uploaded_path);
            }else{
                $image_url = "";
            }
            
            $current_date_time = Carbon::now()->toDateTimeString();
            $post = [
                'title' => $request->title,
                'slug' => $request->slug,
                'body' => $request->body,
                'image' =>   $image_url ,
                'published' => 1,
                'user_id' =>  $id_user,
                'created_at' => $current_date_time
            ];
        
           // dd($post);
            $save_post = Post::insert($post);
            $data= $res;
            return Result::response($data, 'Successfully created');
        } catch(\JWTException $e) {
            return Result::exception(false, 'Server is busy, try again ', 500, config('app.debug')==true?$e:'');
        }
    }
    
    public function get_all_post(Request $request){
        try {
            $user['login'] = auth()->user();
            $id_user = $user['login']->id_user;     
            $role = $user['login']->role;    
            $res = [];   
            if($role==1){                
                $post = Post::join('users', 'users.id', '=', 'posts.user_id')->select('posts.id_post', 'title', 'slug', 'views', 'image','body','posts.created_at','posts.updated_at','username as publisher')->get(); 
            }else{
                $post = Post::where('user_id', $id_user)->join('users', 'users.id', '=', 'posts.user_id')->select('posts.id_post', 'title', 'slug', 'views', 'image','body','posts.created_at','posts.updated_at','username as publisher')->get(); 
            }
            $data= $post;
            return Result::response($data, 'Successfully');
        } catch(\JWTException $e) {
            return Result::exception(false, 'Server is busy, try again ', 500, config('app.debug')==true?$e:'');
        }
    }
    public function get_detail_post(Request $request){
        try {
            $user['login'] = auth()->user();
            $id_post = $request->id_post;   
            $role = $user['login']->role;    
            if($role==1){             
            $post = Post::where('posts.id_post', $id_post)->join('users', 'users.id', '=', 'posts.user_id')->select('posts.id_post', 'title', 'slug', 'views', 'image','body','posts.created_at','posts.updated_at','username as publisher')->first(); 
            }else{
                $post = Post::where('user_id', $id_user)->where('posts.id_post', $id_post)->join('users', 'users.id', '=', 'posts.user_id')->select('posts.id_post', 'title', 'slug', 'views', 'image','body','posts.created_at','posts.updated_at','username as publisher')->first(); 
         
            }
            $data= $post;
            return Result::response($data, 'Successfully');
        } catch(\JWTException $e) {
            return Result::exception(false, 'Server is busy, try again ', 500, config('app.debug')==true?$e:'');
        }
    }
    public function delete_post(Request $request){
        try {
            $user['login'] = auth()->user();
            $id_user = $user['login']->id_user;     
            $role = $user['login']->role;    
            $id_post = $request->id_post;
            $res = [];   
            if($role!=1){                
                $post = Post::get(); 
                $deletedRows = Post::where('id_post', $id_post)->delete();
            }else{
                $deletedRows = Post::where('posts.id_post', $id_post)->join('users', 'users.id', '=', 'posts.user_id')->orWhere('role',2)
                ->orWhere('posts.user_id',$id_user)->delete();     
            }
            if($deletedRows == 1){
                $message = "Successfully";
            }else{
                $message = "Unsuccessfully, you not allowed to delete it";
            }
            $data= $res;
            return Result::response($data, $message);
        } catch(\JWTException $e) {
            return Result::exception(false, 'Server is busy, try again ', 500, config('app.debug')==true?$e:'');
        }
    }
    public function search_post(Request $request){
        try {
            $user['login'] = auth()->user();
            $id_user = $user['login']->id_user;     
            $role = $user['login']->role;    
            $searchTerm = $request->searchTerm;
            $res = [];   
            if($role=1){                
                $post = Post::where('title', 'LIKE', "%{$searchTerm}%") 
                ->orWhere('body', 'LIKE', "%{$searchTerm}%")->join('users', 'users.id', '=', 'posts.user_id')->select('posts.id_post', 'title', 'slug', 'views', 'image','body','posts.created_at','posts.updated_at','username as publisher')->get(); 
            }else{
                $post = Post::where('title', 'LIKE', "%{$searchTerm}%") 
                ->orWhere('body', 'LIKE', "%{$searchTerm}%")
                ->where('user_id', $id_user)->join('users', 'users.id', '=', 'posts.user_id')->select('posts.id_post', 'title', 'slug', 'views', 'image','body','posts.created_at','posts.updated_at','username  as publisher')->get(); 
            }
            $data= $post;
            return Result::response($data, 'Successfully');
        } catch(\JWTException $e) {
            return Result::exception(false, 'Server is busy, try again ', 500, config('app.debug')==true?$e:'');
        }
    }
    public function update_post(Request $request){
        try {
            $user['login'] = auth()->user();
            $id_user = $user['login']->id;     
           // dd($id_user);
            $role = $user['login']->role;    
            $res = [];   
            $id_post = $request->id_post;
            $image = $request->file('image');
            if($image){
                $uploadFolder = 'post';
                $image = $request->file('image');
                $image_uploaded_path = $image->store($uploadFolder, 'public');
                $uploadedImageResponse = array(
                    "image_name" => basename($image_uploaded_path),
                    "image_url" => Storage::disk('public')->url($image_uploaded_path),
                    "mime" => $image->getClientMimeType()
                );
                $image_url = Storage::disk('public')->url($image_uploaded_path);
            }else{
                $image_url = "";
            }
            $current_date_time = Carbon::now()->toDateTimeString();
            $post = [
                'title' => $request->title,
                'slug' => $request->slug,
                'body' => $request->body,
                'image' =>  $image_url,
                'user_id' =>  $id_user,
                'updated_at' => $current_date_time
            ];
        
           // dd($post);
            $post = Post::where('id_post', $id_post)->update($post);
            $data= $res;
            return Result::response($data, 'Successfully updated');
        } catch(\JWTException $e) {
            return Result::exception(false, 'Server is busy, try again ', 500, config('app.debug')==true?$e:'');
        }
    }
}
