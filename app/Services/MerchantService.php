<?php 

namespace App\Services;

use App\Models\Merchant;
use Illuminate\Support\Str;
use App\Traits\Search as Search;
class MerchantService
{

    protected $joinCount = [
        'users', 'merchants',
    ];

    public function findById($id)
    {
        return Merchant::where('id', $id)->first();
    }

    public function fetch($payload = [])
    {
        return Search::generate(Merchant::class, $payload);
    }

    public function store(Array $payload)
    {
        return Merchant::create($payload);
    }

    public function update(Array $payload, String $id)
    {
        $user = Merchant::where('id', $id)->first();
        return $user->update($payload);
    }

    public function delete(String $id)
    {
        return Merchant::find($id)->delete();
    }

    public function getMerchantCode(Array $payload)
    {
        $loop = 0;
        $basecode= Str::of($payload['name'])->replace(' ', '')->upper();
        $code= $basecode;
        $ping = 0;
        
        while($loop < 1)
        {
            $ping++;
           
            $merchant = Merchant::where('merchant_code', $code)->first();
            if(filled($merchant))
            {
                $code = $basecode;
                $code = $code.Str::of(Str::random(6))->upper();
            }
            else
            {
                $loop++;
            }
        }
        return $code;
    }
}
