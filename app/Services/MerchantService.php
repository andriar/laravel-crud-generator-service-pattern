<?php 

namespace App\Services;

use App\Models\Merchant;

class MerchantService
{
    protected $join = [
       'main_merchant'
    ];

    protected $joinCount = [
        'users', 'merchants',
    ];

    public function findById($id)
    {
        return Merchant::where('id', $id)->first();
    }

    public function fetch()
    {
        return Merchant::with($this->join)->withCount($this->joinCount)->get();
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
}
