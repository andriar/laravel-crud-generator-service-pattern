<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
        ]);

        foreach(Role::all() as $role) {
            $users = User::factory(1)->create();
            foreach($users as $user){
                $user->assignRole($role);
            }
         }
    }
}
