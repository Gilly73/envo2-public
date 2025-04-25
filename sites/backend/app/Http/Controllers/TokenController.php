<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TokenController extends Controller
{

    public function getToken(Request $request)
    {
        $user = User::where('email', 'seeta@token.com')->first();
        if (! $user || ! Hash::check('password', $user->password)) {
            $user = User::create([
                'name' => 'Seeta Gill Beaerer Token',
                'email' => 'seeta@token.com',
                'password' => Hash::make('password'),
            ]);
            
        } 
    
        $token = $user->createToken('TestToken')->plainTextToken;
    
        return response()->json(['token' => $token]);
    }

}