<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Services\CategoryService as MS;

class CategoryController extends Controller
{

    protected $categoryService;

    public function __construct(MS $categoryService)
    {
        $this->categoryService = $categoryService;
    }

   public function index(Request $request)
    {
        // try {
            $category = $this->categoryService->fetch($request);
            return \response()->json($category, 200);
        // } catch (\Throwable $th) {
        //     return response()->json([
        //         'error' => $th->getMessage(),
        //         'code' => 'INTERNAL_SERVER_ERROR',
        //     ], 500);
        // }
    }

    public function show($id)
    {
        try {
            $category = $this->categoryService->findById($id);
            return \response()->json($category, 200);
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
            'name' => 'required|string|unique:categories,name,'.$id,
            'parent_id' => 'uuid|exists:categories:id'
        ]);

        try {
            $category = $this->categoryService->store($request->all());
            return \response()->json($category, 201);
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
            'name' => 'string|unique:categories,name,'.$id,
            'parent_id' => 'uuid|exists:categories:id'
        ]);

        try {
            $category = $this->categoryService->update($request->all(), $id);
            return \response()->json($category, 200);
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
            $category = $this->categoryService->delete($id);
            return \response()->json($category, 200);
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
            $category = $this->categoryService->permanentDelete($id);
            return \response()->json($category, 200);
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
            $category = $this->categoryService->restore($id);
            return \response()->json($category, 200);
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
            $category = $this->categoryService->fetch($request);
            return \response()->json($category, 200);
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
            $category = $this->categoryService->fetch($request);
            return \response()->json($category, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'code' => 'INTERNAL_SERVER_ERROR',
            ], 500);
        }
    }
}