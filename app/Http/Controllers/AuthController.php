<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'cpf' => 'required|string|unique:users|max:11',
            'password' => 'required|string|confirmed',
            'type' => 'string'
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'type' => $request->type,
            'cpf' => $request->cpf,
            'password' => bcrypt($request->password)

        ]);
        $user->save();
        return response()->json([
            'res' => 'User created'
        ], 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'cpf' => 'required|string|max:11',
            'password' => 'required|string'
        ]);
        $credentials = [
            'cpf' =>    $request->cpf,
            'password' => $request->password,
        ];
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'res' => 'Invalid credentials'
            ], 401);
        }
        $user = User::where('cpf', $request->cpf)->first();
        if ($user->type == 'admin')
            $token = $user->createToken('Token', ['admin-roles'])->accessToken;
        else
            $token = $user->createToken('Token')->accessToken;
        return response()->json([
            'token' => $token
        ], 200);
    }
    public function logout(Request $request)
    {
        $request->user()->token->revoke();
        return response()->json(['Success logout'], 200);
    }
}
