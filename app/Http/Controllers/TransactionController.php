<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Services\TransactionService as MS;
use App\Services\TransactionDetailService as TDS;
use App\Services\ProductService as PS;

class TransactionController extends Controller
{

    protected $transactionService;
    protected $transactionDetailService;
    protected $productService;

    public function __construct(
        MS $transactionService, 
        TDS $transactionDetailService,
        PS $productService
    )
    {
        $this->transactionService = $transactionService;
        $this->transactionDetailService = $transactionDetailService;
        $this->productService = $productService;
    }

   public function index(Request $request)
    {
        try {
            $transaction = $this->transactionService->fetch($request);
            return \response()->json($transaction, 200);
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
            $transaction = $this->transactionService->findById($id);
            return \response()->json($transaction, 200);
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
            'name' => 'string',
            'table_number' => 'string',
            'description' => 'string',
            'status' => 'string|in:UNPAID,PAID,CANCEL',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|uuid|exists:products,id',
            'products.*.qty' => 'required|numeric',
            // 'promo_ids' => 'required|array|min:1',
            // 'promo_ids.*.id' => 'uuid|exists:promos,id',
            'total_price_product' => 'required|numeric',
            'total_price' => 'required|numeric',
        ]);

        try {
            $transaction = $this->transactionService->store($request->all());

            foreach ($request->products as $product) {
                $productDetail = $this->productService->findById($product['id']);

                $product['transaction_id'] = $transaction['id'];
                $product['detail_id'] = $product['id'];
                $product['type'] = 'PRODUCT';
                $product['meta'] =  $productDetail;
                $this->transactionDetailService->store($product);

                if($request->status == 'PAID')
                {
                    $this->productService->decreaseStock($product);
                }
            }

            return \response()->json($transaction, 201);
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
            'table_number' => 'string',
            'description' => 'string',
            'status' => 'string|in:UNPAID,PAID,CANCEL',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|uuid|exists:products,id',
            'products.*.qty' => 'required|numeric',
            // 'promo_ids' => 'required|array|min:1',
            // 'promo_ids.*.id' => 'uuid|exists:promos,id',
            'total_price_product' => 'required|numeric',
            'total_price' => 'required|numeric',
        ]);

        try {
            $transaction = $this->transactionService->findById($id);
            $transactionUpdate = $this->transactionService->update($request->all(), $id);

            //delete child records
            $this->transactionDetailService->deleteByTransaction($id);

            foreach ($request->products as $product) {
                $productDetail = $this->productService->findById($product['id']);

                $product['transaction_id'] = $id;
                $product['detail_id'] = $product['id'];
                $product['type'] = 'PRODUCT';
                $product['meta'] =  $productDetail;
                $this->transactionDetailService->store($product);

                if($request->status == 'PAID' && $transaction['status'] != 'PAID')
                {
                    $this->productService->decreaseStock($product);
                }
            }
            return \response()->json($transaction, 200);
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
            $transaction = $this->transactionService->delete($id);
            return \response()->json($transaction, 200);
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
            $transaction = $this->transactionService->permanentDelete($id);
            return \response()->json($transaction, 200);
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
            $transaction = $this->transactionService->restore($id);
            return \response()->json($transaction, 200);
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
            $transaction = $this->transactionService->fetch($request);
            return \response()->json($transaction, 200);
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
            $transaction = $this->transactionService->fetch($request);
            return \response()->json($transaction, 200);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'code' => 'INTERNAL_SERVER_ERROR',
            ], 500);
        }
    }
}