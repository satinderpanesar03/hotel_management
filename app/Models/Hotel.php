<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $appends = [
        'hotel_images'
    ];

    protected $fillable = ['hotel', 'state_id', 'city'];

    public function images()
    {
        return $this->hasMany(HotelImages::class);
    }

    public function state(){
        return $this->belongsTo(State::class)->select('id','state');
    }

    public function getHotelImagesAttribute(){
        $images = [];

        foreach($this->images as $image){
            array_push($images, asset('storage/'. $image->images));
        }

        return $images;
    }

}
