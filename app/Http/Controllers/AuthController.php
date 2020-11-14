<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\UserService as US;
use App\Services\AuthService as AuS;
use App\Services\MerchantService as MeS;
use Illuminate\Support\Facades\Crypt;
class AuthController extends Controller
{
    protected $userService;
    protected $authService;
    protected $merchantService;

    public function __construct(US $userService, AuS $authService, Mes $merchantService)
    {
        $this->userService = $userService;
        $this->authService = $authService;
        $this->merchantService = $merchantService;
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // try {
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
        // } catch (\Throwable $th) {
        //     return response()->json([
        //         'error' => $th->getMessage(),
        //         'code' => 'INTERNAL_SERVER_ERROR',
        //     ], 500);
        // }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'success logout !'], 200);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'merchant_name' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|numeric|min:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $merchantData = ([
                'name' => $request->merchant_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
            ]);

            $code = $this->merchantService->getMerchantCode($merchantData);
            
            $merchantData['merchant_code'] = $code;

            $merchant = $this->merchantService->store($merchantData);
            
            $userData = $validated;
            $userData['role'] = "OWNER";
            $userData['merchant_id'] = $merchant->id;
            $user = $this->userService->store($userData);
            return \response()->json($user, 201);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'code' => 'INTERNAL_SERVER_ERROR',
            ], 500);
        }
    }

    public function activation(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        try {
            // TODO:: Cara Enkripsi dan deskripsi token
            // $text = 'cf2ed840-e323-4534-94d7-9a8507fd4403';
            // $encrypted = Crypt::encryptString($text);
            
            $decryptedId = Crypt::decryptString($request->token);
            $user = $this->userService->findById($decryptedId);

            if($user && $user['email_verified_at' === NULL])
            {
                $this->authService->verified($user['id']);

                return \response()->json([
                    'message' => 'activation user successfull !',
                ], 200);
            }
            else
            {
                return \response()->json([
                    'message' => 'invalid token'
                ], 500);
            }


            return \response()->json([
                'message' => 'activation user successfull !'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'code' => 'INTERNAL_SERVER_ERROR',
            ], 500);
        }
    }
}
