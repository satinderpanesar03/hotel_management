<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StaeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $json = \Illuminate\Support\Facades\File::get("database/data/states.json");
        $data = json_decode($json);
        foreach ($data as $state) {
            State::updateOrCreate([
                'state' => $state->name,
            ]);
        }
    }
}
