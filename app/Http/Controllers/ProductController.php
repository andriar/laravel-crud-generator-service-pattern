<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\ProductService as MS;
use App\Services\ImageService as IS;

class ProductController extends Controller
{

    protected $productService;
    protected $imageService;

    public function __construct(MS $productService, IS $imageService)
    {
        $this->productService = $productService;
        $this->imageService = $imageService;
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
            'merchant_id' => 'required|uuid|exists:merchants,id',
            'promo_ids' => 'json',
            'weight' => 'numeric',
            'height' => 'numeric',
            'length' => 'numeric',
            'size' => 'numeric',
            'stock' => 'required_if:is_stockable,1',
            'category_ids' => 'array',
            'category_ids.*' => 'uuid|exists:categories,id',
            'images' => 'array',
            'images.*' => 'file|mimes:jpeg,jpg|dimensions:ratio=1/1'
        ]);

        try {
            $images = [];
            if(filled($request->images))
            {
                foreach ($request->images as $image) {
                    $image = $this->imageService->store($image);
                    array_push($images, $image['id']);
                }
            }
            
            $data = $request->except('images');
            $data['image_ids'] = $images;
           


            $product = $this->productService->store($data);
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
            'merchant_id' => 'uuid|exists:merchants,id',
            'promo_ids' => 'json',
            'weight' => 'numeric',
            'height' => 'numeric',
            'length' => 'numeric',
            'size' => 'numeric',
            'category_ids' => 'array',
            'category_ids.*' => 'uuid|exists:categories,id',
            'images' => 'array',
            'images.*' => 'file|mimes:jpeg,jpg|dimensions:ratio=1/1',
            'image_ids' => 'array',
            'image_ids.*' => 'uuid|exists:images,id'
        ]);

        try {
            $images = [];
            $data = $request->except('images');
            if(($request->images))
            {
                foreach ($request->images as $image) {
                    $image = $this->imageService->store($image);
                    array_push($images, $image['id']);
                }
                $data['image_ids'] = $request->image_ids ? array_merge($images, $request->image_ids) : $images;
            }
            
            $product = $this->productService->update($data, $id);
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
            $product = $this->productService->findById($id);
            foreach ($product['image_ids'] as $imageId) {
                $image = $this->imageService->delete($imageId);
            }
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