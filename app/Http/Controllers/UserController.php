<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService as US;
use App\Services\MailService as MS;
class UserController extends Controller
{
    protected $userService;
    protected $mailService;

    public function __construct(US $userService, MS $mailService)
    {
        $this->userService = $userService;
        $this->mailService = $mailService;
    }

    public function index()
    {
        try {
            $user = $this->userService->fetch();
            return \response()->json($user, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'code' => 'INTERNAL_SERVER_ERROR',
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|min:6',
            'is_active' => 'required|boolean',
            'password' => 'required|string|min:8',
            'role' => 'string|in:SUPERADMIN,EMPLOYEE,OWNER,CASHIER,CHEF',
        ]);

        try {
            if(!$request->role)
            {
                $request->merge([
                    'role' => 'EMPLOYEE',
                ]);
            }
            $user = $this->userService->store($request->all());
            // $this->mailService->verificationMail($user);
            return \response()->json($user, 201);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'code' => 'INTERNAL_SERVER_ERROR',
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$id,
            'phone_number' => 'required|string|min:6',
            'is_active' => 'required|boolean',
            'password' => 'required|string|min:8',
        ]);

        try {
            $user = $this->userService->update($request->all(), $id);
            return \response()->json($user, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'code' => 'INTERNAL_SERVER_ERROR',
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $user = $this->userService->delete($id);
            return \response()->json($user, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'code' => 'INTERNAL_SERVER_ERROR',
            ], 500);
        }
    }

    public function deletePermanent($id)
    {
        try {
            $user = $this->userService->permanentDelete($id);
            return \response()->json($user, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'code' => 'INTERNAL_SERVER_ERROR',
            ], 500);
        }
    }

    public function restore($id)
    {
        try {
            $user = $this->userService->restore($id);
            return \response()->json($user, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'code' => 'INTERNAL_SERVER_ERROR',
            ], 500);
        }
    }

    public function withtrashed(Request $request)
    {
        try {
            $user = $this->userService->fetchWithTrashed();
            return \response()->json($user, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'code' => 'INTERNAL_SERVER_ERROR',
            ], 500);
        }
    }

    public function onlytrashed(Request $request)
    {
        try {
            $user = $this->userService->fetchOnlyTrashed();
            return \response()->json($user, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'code' => 'INTERNAL_SERVER_ERROR',
            ], 500);
        }
    }
}
