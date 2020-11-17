<?php 

namespace App\Services;

use App\Models\Transaction;
use App\Models\Merchant;
use App\Traits\Search as Search;
class TransactionService
{
    protected $join = [];

    public function findById(String $id)
    {
       return Transaction::with($this->join)->where('id', $id)->first();
    }

    public function fetch($payload = [])
    {
        return Search::generate(Transaction::class, $payload);
    }

    public function store(Array $payload)
    {
        $payload['transaction_code'] = $this->generateTransactionCode();
        $payload['merchant_id'] = auth()->user()->merchant_id;
        $transaction = Transaction::create($payload);
        return $transaction;
    }

    public function update(Array $payload, String $id)
    {
        $transaction = Transaction::where('id', $id)->first();
        return $transaction->update($payload);
    }

    public function delete(String $id)
    {
        return Transaction::find($id)->delete();
    }

    public function permanentDelete(String $id)
    {
        return Transaction::where('id', $id)->withTrashed()->forceDelete();
    }

    public function restore(String $id)
    {
        return Transaction::where('id', $id)->withTrashed()->restore();
    }

    public function generateTransactionCode()
    {
        $merchant = Merchant::where('id', auth()->user()->merchant_id)->first();
        $lastTransaction = Transaction::whereDate('created_at', today())
        ->orderBy('created_at', 'DESC')
        ->first();

        $code = $merchant['merchant_code'].'-'.strtotime(now());

        if($lastTransaction)
        {
            // $code 
            $codeSplitter = explode("-", $lastTransaction['transaction_code']);
            $noTrans = ((int) $codeSplitter[2]) + 1;
            if($noTrans < 10)
            {
                $noTrans  = '000'.$noTrans;
            }
            else if($noTrans < 100)
            {
                $noTrans  = '00'.$noTrans;
            }
            else if($noTrans < 1000)
            {
                $noTrans  = '0'.$noTrans;
            }

            $code = $code.'-'.$noTrans; 
        }
        else
        {
            $code = $code.'-'.'0001'; 
        }

        return $code;
    }
}
