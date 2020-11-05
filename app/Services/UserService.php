<?php 

namespace App\Services;

use App\Models\User;

class UserService
{
    public function findByMail(String $mail)
    {
       return User::where('email', $mail)->first();
    }
}
