<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{

    public function redirectProvider()
    {
        return Socialite::driver('google')->redirect();
    }


    public function handleCallBack()
    {
        try {
            $user = Socialite::driver('google')->user();
        }
        catch (\Exception $e) {
            return redirect ('/login')->with('alert-warning','Login to google first!');
        }
        // dd($user);

       
        // $existingUser = User::where('google_id', $user->id)->first();
        $existingUser = User::where('email', $user->email)->first();

        if($existingUser) {
            Auth::login($existingUser, true);
        }
        else {
            $newUser = User::create ([
                'name'      => $user->name,
                'email'     => $user->email,
                'google_id' => $user->id,
            ]);
          
            Auth::login($newUser, true);
        }

        return redirect()->to('/dashboard')->with('alert-success','You have logged in successfully!');
    }
}
