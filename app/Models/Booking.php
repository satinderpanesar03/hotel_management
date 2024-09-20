<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'user_id',
        'check_in',
        'check_out',
        'price'
    ];

    public function room(){
        return $this->belongsTo(Room::class);
    }


    public function user(){
        return $this->belongsTo(User::class);
    }

}
