<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $item_type = ItemType::create([
            'name' => 'Services',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        Item::insert([
            [
                'item_type_id' => $item_type->id,
                'name' => 'Design',
                'price' => 230,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'item_type_id' => $item_type->id,
                'name' => 'Development',
                'price' => 330,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'item_type_id' => $item_type->id,
                'name' => 'Meetings',
                'price' => 60,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ]);
    }
}
