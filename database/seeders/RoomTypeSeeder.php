<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Single Rooms',
            'Twin or Double Rooms',
            'Queen Rooms',
            'Triple Rooms',
            'Deluxe Rooms'
        ];

        foreach($types as $type){
            RoomType::create([
                'type' => $type
            ]);
        }
        
    }
}
