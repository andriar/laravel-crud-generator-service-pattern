<?php 

namespace App\Services;

use App\Models\TransactionDetail;
use App\Traits\Search as Search;
class TransactionDetailService
{
    public function findById(String $id)
    {
       return TransactionDetail::with($this->join)->where('id', $id)->first();
    }

    public function fetch($payload = [])
    {
        return Search::generate(TransactionDetail::class, $payload);
    }

    public function store(Array $payload)
    {
        $transaction = TransactionDetail::create($payload);
        return $transaction;
    }

    public function update(Array $payload, String $id)
    {
        $transaction = TransactionDetail::where('id', $id)->first();
        return $transaction->update($payload);
    }

    public function delete(String $id)
    {
        return TransactionDetail::find($id)->delete();
    }

    public function deleteByTransaction(String $id)
    {
        return TransactionDetail::where('transaction_id', $id)->delete();
    }
}
