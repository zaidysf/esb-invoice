<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Client::insert([
            'name' => 'Barrington Publishers',
            'email' => Str::random(5).'@barrington-publishers.com',
            'phone' => random_int(8,11),
            'address1' => '17 Great Suffolk Street',
            'address2' => 'London SE1 0NS',
            'country' => 'United Kingdom',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
