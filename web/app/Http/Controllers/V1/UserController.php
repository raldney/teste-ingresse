<?php

namespace App\Http\Controllers\V1;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Cache;

class UserController extends Controller
{

    public function showAllUsers(Request $request){
        $currentPage = (isset($request->page) ? $request->page : 1);
        $limit = (isset($request->limit) ? $request->limit : 10);
        return response()->json($this->paginated($limit,$currentPage));
    }

    public function paginated($limit = 10, $currentPage = 1,$trashed = false){
        $expiration = 1; //minutes
        $key = "user_" . ($trashed ? 'trashed_' : '') . $currentPage;
        
        if(!Cache::has($key)){
            $skip = $limit * ($currentPage - 1);
            if(!$trashed){
                Cache::put($key, User::skip($skip)->take($limit)->get(), $expiration);
            }
            else{
                Cache::put($key, User::onlyTrashed()->skip($skip)->take($limit)->get(), $expiration);

            }

        }
        
        return  Cache::get($key);
        
        
  
    }

    public function showOneUser($id){
        return response()->json(User::find($id));
    }

    public function create(Request $request){
        $this->validate($request,[
            'name' => 'required|max:255',
            'email' => 'required|unique:users|max:255',
            'password' => 'required|confirmed|max:255'
        ]);

        $user = new User($request->all());
        
        $user->password = Hash::make($request->password);
        $user->save();
        // $user = User::create();

        return response()->json($user, 201);
    }

    public function update($id, Request $request){

        $changePass = false; 
        $validateData = [
            'name' => 'required|max:255',
            'email' => 'required|unique:users|max:255',
        ];

        if(isset($request->all()['password'])){
            $validateData['password'] = 'required|confirmed|max:255';
            $changePass = true;
        }
        $this->validate($request,$validateData);

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if($changePass)
            $user->password = Hash::make($request->password);
        $user->update();

        return response()->json($user, 200);
    }

    public function delete($id){
        if(User::destroy($id))
            return response('Deleted Successfully', 200);
        return response('Delete error', 401);
    }

    public function showAllTrashedUsers(Request $request){
        $currentPage = (isset($request->page) ? $request->page : 1);
        $limit = (isset($request->limit) ? $request->limit : 10);
        return response()->json($this->paginated($limit,$currentPage,true));
    }
}