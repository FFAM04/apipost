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
class UserController extends Controller
{
    public function add_user(Request $request){
        try {
            $user['login'] = auth()->user();
            $id_user = $user['login']->id;     
            $role = $user['login']->role;    
            $res = [];               
            if($role==1){  
                $validator = Validator::make($request->all(), [
                    'username' => 'required|string|between:2,100',
                    'email' => 'required|string|email|max:100|unique:users',
                    'role' => 'required',
                ]);
        
                if($validator->fails()){
                    return response()->json($validator->errors()->toJson(), 400);
                }
        
                $current_date_time = Carbon::now()->toDateTimeString();
                $post = [
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' =>bcrypt($request->password) ,
                    'role' =>  $request->role ,
                    'created_at' => $current_date_time
                ];
            
               // dd($post);
                $save_post = User::insert($post);
                $data= $res;      
                
                $data= $res;
                return Result::response($data, 'Successfully created');
            }else{
                    
            $data= $res;
            return Result::response($data, 'You not allowed to use this api');
            }
        } catch(\JWTException $e) {
            return Result::exception(false, 'Server is busy, try again ', 500, config('app.debug')==true?$e:'');
        }
    }
    public function get_user(Request $request){
        try {
            $user['login'] = auth()->user();
            $id_user = $user['login']->id_user;     
            $role = $user['login']->role;    
            $res = [];   
            if($role==1){                
                $post = User::get();                 
                $data= $post;
                return Result::response($data, 'Successfully');
            }else{                
                $data= $res;
                return Result::response($data, 'You not allowed to use this api'); 
            }
        } catch(\JWTException $e) {
            return Result::exception(false, 'Server is busy, try again ', 500, config('app.debug')==true?$e:'');
        }
    }
    public function get_detail_user(Request $request){
        try {
            $user['login'] = auth()->user();
            $id_post = $request->id_user;   
            $role = $user['login']->role;    
            if($role==1){             
                $post = User::where('id', $id_post)->first();             
                $data= $post;
                return Result::response($data, 'Successfully');
            }else{                
                $data= $res;
                return Result::response($data, 'You not allowed to use this api'); 
            }
        } catch(\JWTException $e) {
            return Result::exception(false, 'Server is busy, try again ', 500, config('app.debug')==true?$e:'');
        }
    }
    public function delete_user(Request $request){
        try {
            $user['login'] = auth()->user();
            $id_user = $user['login']->id_user;     
            $role = $user['login']->role;    
            $id_post = $request->id_user;
            $res = [];   
            if($role==1){               
                $deletedRows = User::where('id', $id_post)->delete();                
                $data= $res;                
                $message = "Successfully deleted";
                return Result::response($data, $message);
            }else{                          
                $data= $res;
                return Result::response($data, 'You not allowed to use this api');      
            }
        } catch(\JWTException $e) {
            return Result::exception(false, 'Server is busy, try again ', 500, config('app.debug')==true?$e:'');
        }
    }
    public function search_user(Request $request){
        try {
            $user['login'] = auth()->user();
            $id_user = $user['login']->id_user;     
            $role = $user['login']->role;    
            $searchTerm = $request->searchTerm;
            $res = [];   
            if($role=1){                
                $post = User::where('username', 'LIKE', "%{$searchTerm}%")->get();                 
                $data= $post;
                return Result::response($data, 'Successfully');
            }else{
                $data= $res;
                return Result::response($data, 'You not allowed to use this api');       
            }
        } catch(\JWTException $e) {
            return Result::exception(false, 'Server is busy, try again ', 500, config('app.debug')==true?$e:'');
        }
    }
    public function update_user(Request $request){
        try {
            $user['login'] = auth()->user();
            $id_user = $user['login']->id;     
            $role = $user['login']->role;   
            $id_user_ = $request->id_user;
            $res = [];               
            if($role==1){                  
                $current_date_time = Carbon::now()->toDateTimeString();
                $post = [
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' =>bcrypt($request->password) ,
                    'role' =>   $request->role,
                    'updated_at' => $current_date_time
                ];
                $post = User::where('id', $id_user_)->update($post);
                $data= $res;
                return Result::response($data, 'Successfully updated');
            }else{
                    
            $data= $res;
            return Result::response($data, 'You not allowed to use this api');
            }
        } catch(\JWTException $e) {
            return Result::exception(false, 'Server is busy, try again ', 500, config('app.debug')==true?$e:'');
        }
    }
}
