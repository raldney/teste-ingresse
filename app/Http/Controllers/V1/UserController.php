<?php

namespace App\Http\Controllers\V1;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function showAllUsers()
    {
        return response()->json(User::all());
    }

    public function showOneUser($id)
    {
        return response()->json(User::find($id));
    }

    public function create(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|max:255',
            'email' => 'required|unique:users|max:255',
            'name' => 'required|max:255'
        ]);

        $user = new User($request->all());
        $user->password = Hash::make($request->password);
        $user->save();
        // $user = User::create();

        return response()->json($user, 201);
    }

    public function update($id, Request $request)
    {

        $this->validate($request,[
            'name' => 'required|max:255',
            'email' => 'required|unique:users|max:255',
            'name' => 'required|max:255'
        ]);
        
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json($user, 200);
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}