<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    CONST LISTED = 1;

    protected $fillable = [
        'hotel_id',
        'room',
        'room_type_id',
        'number_of_guests',
        'price',
        'listed',
    ];

    protected $appends = [
        'room_images',
        'amenity_name'
    ];

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'amenity_room');
    }

    public function images()
    {
        return $this->hasMany(RoomImage::class);
    }

    public function hotel(){
        return $this->belongsTo(Hotel::class)->select('id','hotel','state_id','city');
    }

    public function type(){
        return $this->belongsTo(RoomType::class, 'room_type_id')->select('id','type');
    }

    public function getRoomImagesAttribute(){
        $images = [];

        foreach($this->images as $image){
            array_push($images, asset('storage/'. $image->path));
        }

        return $images;
    }

    public function getAmenityNameAttribute(){
        $amenties = [];

        foreach($this->amenities as $amenity){
            array_push($amenties, $amenity->amenity);
        }

        return $amenties;
    }

}
