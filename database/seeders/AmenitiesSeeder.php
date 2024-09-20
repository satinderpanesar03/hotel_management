<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AmenitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $amenities = [
              "Free Wi-Fi",
              "Swimming Pool",
              "Fitness Center",
              "Spa",
              "Business Center",
              "Airport Shuttle",
              "Pet Friendly",
              "On-site Restaurant",
              "Room Service",
              "Bar/Lounge",
              "Laundry Service",
              "24-Hour Front Desk",
              "Concierge Service",
              "Meeting Rooms",
              "Parking",
              "Dry Cleaning",
              "Golf Course",
              "Beach Access",
              "Free Breakfast",
              "Disabled Access"
       ];

       foreach($amenities as $item){
        Amenity::create([
            'amenity' => $item
           ]);
       }
       
          
    }
}
