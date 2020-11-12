<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MerchantService as MeS;

class MerchantController extends Controller
{
    protected $merchantService;

    public function __construct(MeS $merchantService)
    {
        $this->merchantService = $merchantService;
    }

    public function index()
    {
        try {
            $merchant = $this->merchantService->fetch();
            return \response()->json($merchant, 200);
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
            'name' => 'required|string',
            'phone_number' => 'required|email|unique:merchants,email',
            'phone_number' => 'required|numeric|unique:merchants,phone_number',
            'is_active' => 'required|boolean',
            'merchant_id' => 'uuid|exists:merchants,id',
        ]);

        try {
            $code = $this->merchantService->getMerchantCode($request->all());
            $request->merge([
                'merchant_code' => $code
            ]);

            $merchant = $this->merchantService->store($request->all());
            return \response()->json($merchant, 201);
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
            'name' => 'required|string',
            'email' => 'required|email|unique:merchants,email,'.$id,
            'phone_number' => 'required|numeric|unique:merchants,phone_number,'.$id,
            'is_active' => 'required|boolean',
            'merchant_id' => 'uuid|exists:merchants,id',
        ]);

        try {
            $merchant = $this->merchantService->update($request->all(), $id);
            return \response()->json($merchant, 200);
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
            $merchant = $this->merchantService->delete($id);
            return \response()->json($merchant, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'code' => 'INTERNAL_SERVER_ERROR',
            ], 500);
        }
    }
}
