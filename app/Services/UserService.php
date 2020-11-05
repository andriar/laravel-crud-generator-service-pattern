<?php 

namespace App\Services;

use App\Models\User;

class UserService
{
    public function findByMail(String $mail)
    {
       return User::where('email', $mail)->first();
    }

    public function findById(String $id)
    {
       return User::where('id', $id)->first();
    }

    public function fetch()
    {
        return User::get();
    }

    public function fetchWithTrashed()
    {
        return User::withTrashed()->get();
    }

    public function fetchOnlyTrashed()
    {
        return User::onlyTrashed()->get();
    }

    public function store(Array $payload)
    {
        $payload['password'] = bcrypt($payload['password']);
        $payload['full_name'] = $payload['first_name'].' '.$payload['last_name'];
        return User::create($payload);
    }

    public function update(Array $payload, String $id)
    {
        $user = User::where('id', $id)->first();
        $payload['full_name'] = $payload['first_name'].' '.$payload['last_name'];
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
