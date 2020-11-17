<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Services\ImageService as MS;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{

    protected $imageService;

    public function __construct(MS $imageService)
    {
        $this->imageService = $imageService;
    }

   public function index(Request $request)
    {
        try {
            $image = $this->imageService->fetch($request);
            return \response()->json($image, 200);
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
            $image = $this->imageService->findById($id);
            return \response()->json($image, 200);
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
            'images' => 'required|array',
            'images.*' => 'file|mimes:jpeg,jpg|dimensions:ratio=1/1'
        ]);

        try {
            foreach ($request->images as $image) {
                $this->imageService->store($image);
            }
            return \response()->json([
                'message' => 'upload success !'
            ], 201);
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
            $image = $this->imageService->delete($id);
            return \response()->json($image, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'code' => 'INTERNAL_SERVER_ERROR',
            ], 500);
        }
    }
}