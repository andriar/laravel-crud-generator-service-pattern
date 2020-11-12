<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Merchant;

class MerchantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchant = Merchant::create([
            'name' => 'coffee shop 8',
            'merchant_code' => 'COFFEE8',

        ]);

        foreach(User::all() as $user) {
            $user->update([
                'merchant_id' => $merchant['id']
            ]);
         }
    }
}
