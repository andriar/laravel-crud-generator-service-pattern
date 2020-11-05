<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\UserService as US;
use App\Services\AuthService as AuS;

class AuthController extends Controller
{
    protected $userService;
    protected $authService;

    public function __construct(US $userService, AuS $authService)
    {
        $this->userService = $userService;
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        try {
            $userData = $this->userService->findByMail($request->email);

            if($userData)
            {
                if($userData['email_verified_at'] === null || $userData['is_active'] !== 1)
                {
                    return response()->json([
                        'error' => 'user need activation !',
                        'code' => 'USER_NEED_ACTIVATION'
                    ], 401);
                }
                else
                {
                    $data = [
                        'email' => $request->email,
                        'password' => $request->password
                    ];
                    
                    $login = $this->authService->login($data);

                    if ($login) 
                    {
                        return response()->json($login, 200);
                    } 
                    else 
                    {
                        return response()->json([
                            'error' => 'Unauthorized',
                            'code' => 'UNAUTHORIZED'
                        ], 401);
                    }
                }
            }
            else
            {
                return response()->json([
                    'error' => 'the user is not registered yet',
                    'code' => 'USER_NOT_REGISTERED'
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'code' => 'INTERNAL_SERVER_ERROR',
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'success logout !'], 200);
    }
}
