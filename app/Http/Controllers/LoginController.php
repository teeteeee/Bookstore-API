<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    public function register(Request $request){
        $this->validate($request, [
            'name' => "required|min:3",
            'email' => 'required|unique:users|string',
            'password' => 'required|string'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' =>$request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('Teetee')->accessToken;

        return response()->json(['token' => $token], 200);
    }

    public function login(Request $request)
    {
        $login = $request->validate([
            'email' => 'required',
            'password'=> 'required',
        ]);
        if (!Auth::attempt($login)){
            return response()->json(['message' => 'error']);
        }

        /** @var \App\Models\UserModel $user **/
        $user = Auth::user();
        $token = $user->createToken('Token Name')->accessToken;
        return response()->json(['user' => $user, 'token' => $token]);
    }
}
