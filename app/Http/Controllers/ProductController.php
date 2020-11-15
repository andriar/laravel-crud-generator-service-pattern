<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\ProductService as MS;

class ProductController extends Controller
{

    protected $productService;

    public function __construct(MS $productService)
    {
        $this->productService = $productService;
    }

   public function index(Request $request)
    {
        try {
            $product = $this->productService->fetch($request);
            return \response()->json($product, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'code' => 'INTERNAL_SERVER_ERROR',
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = $this->productService->findById($id);
            return \response()->json($product, 200);
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
            'description' => 'string',
            'selling_price' => 'required|numeric',
            'base_price' => 'required|numeric',
            'final_price' => 'required|numeric',
            'is_stockable' => 'required|boolean',
            'is_visible' => 'required|boolean',
            // 'categories' => 'json',
            'merchant_id' => 'required|uuid|exists:merchants,id',
            'promos' => 'json',
            // 'images' => 'array',
            'weight' => 'numeric',
            'height' => 'numeric',
            'length' => 'numeric',
            'size' => 'numeric',
            // 'type' => 'numeric',
            'stock' => 'required_if:is_stockable,1'
        ]);

        try {
            $product = $this->productService->store($request->all());
            return \response()->json($product, 201);
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
            'name' => 'string',
            'description' => 'string',
            'selling_price' => 'numeric',
            'base_price' => 'numeric',
            'final_price' => 'numeric',
            'is_stockable' => 'boolean',
            'is_visible' => 'boolean',
            // 'categories' => 'json',
            'merchant_id' => 'uuid|exists:merchants,id',
            'promos' => 'json',
            // 'images' => 'array',
            'weight' => 'numeric',
            'height' => 'numeric',
            'length' => 'numeric',
            'size' => 'numeric',
            // 'type' => 'numeric',
        ]);

        try {
            $product = $this->productService->update($request->all(), $id);
            return \response()->json($product, 200);
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
            $product = $this->productService->delete($id);
            return \response()->json($product, 200);
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
            $product = $this->productService->permanentDelete($id);
            return \response()->json($product, 200);
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
            $product = $this->productService->restore($id);
            return \response()->json($product, 200);
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
            $request->merge([
                'with_trashed' => true,
            ]);
            $product = $this->productService->fetch($request);
            return \response()->json($product, 200);
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
            $request->merge([
                'only_trashed' => true,
            ]);
            $product = $this->productService->fetch($request);
            return \response()->json($product, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'code' => 'INTERNAL_SERVER_ERROR',
            ], 500);
        }
    }

    public function updatestock(Request $request, $productId)
    {
        $request->validate([
            'stock' => 'required|numeric'
        ]);

        try {
            $product = $this->productService->updateStock($request->all(), $productId);
            return \response()->json($product, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'code' => 'INTERNAL_SERVER_ERROR',
            ], 500);
        }
    }
}