<?php 

namespace App\Services;
use App\Models\User;

class AuthService
{
    public function login(Array $payload)
    {
        if(auth()->attempt($payload))
        {
            $token = auth()->user()->createToken('COFFEE8TOKEN')->accessToken;

            $userData = User::where('email', $payload['email'])->first();
            $userData->update([
                'last_login' => date_create()
            ]);

            return [
                'token_type' => 'Bearer',
                'token' => $token,
                'user' => $userData
            ];
        }
        else
        {
            return [];
        }
    }

    public function verified(String $id)
    {
        return User::where('id', $id)->update([
            'email_verified_at' => date_create()
        ]);
    }

}
