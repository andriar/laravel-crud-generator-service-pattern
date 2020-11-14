<?php 

namespace App\Services;

use App\Models\User;
use App\Traits\Search as Search;
class UserService
{
    protected $join = [
        'roles', 'merchant'
    ];

    public function findByMail(String $mail)
    {
       return User::with($this->join)->where('email', $mail)->first();
    }

    public function findById(String $id)
    {
       return User::with($this->join)->where('id', $id)->first();
    }

    public function fetch($payload = [])
    {
        return Search::generate(User::class, $payload);
    }

    public function store(Array $payload)
    {
        $payload['password'] = bcrypt($payload['password']);
        
        $user = User::create($payload);
        $user->assignRole($payload['role']);
        
        return $user;
    }

    public function update(Array $payload, String $id)
    {
        $user = User::where('id', $id)->first();
        return $user->update($payload);
    }

    public function updatePassword(Array $payload, String $id)
    {
        $user = User::where('id', $id)->first();
        return $user->update([
            'password' => bcrypt($payload->password)   
        ]);
    }

    public function delete(String $id)
    {
        return User::find($id)->delete();
    }

    public function permanentDelete(String $id)
    {
        return User::where('id', $id)->withTrashed()->forceDelete();
    }

    public function restore(String $id)
    {
        return User::where('id', $id)->withTrashed()->restore();
    }
}
