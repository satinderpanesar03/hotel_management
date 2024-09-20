<?php

namespace App\Http\Controllers\api\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Exception;


class DashboardController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $roomQuery = Room::query()->with(['hotel', 'hotel.state', 'type','images','amenities']);
        $hotelQuery = Hotel::query()->with('state','images');

        if ($request->search) {
            $rooms = $roomQuery
                ->whereHas('hotel', function ($query) use ($request) {
                    $query->where('hotel', 'LIKE', '%' . $request->search . '%');
                })
                ->orWhere('room', 'LIKE', '%' . $request->search . '%')
                ->where('listed', Room::LISTED)
                ->get();

            $hotels = $hotelQuery
                ->where('hotel', 'LIKE', '%' . $request->search . '%')
                ->get();
        }

        if ($request->location) {
            $rooms = $roomQuery
                ->whereHas('hotel', function ($query) use ($request) {
                    $query->where('city', 'LIKE', '%' . $request->location . '%');
                })
                ->orWhereHas('hotel.state', function ($query) use ($request) {
                    $query->where('state', 'LIKE', '%' . $request->location . '%');
                })
                ->get();

            $hotels = $hotelQuery
                ->where('city', 'LIKE', '%' . $request->location . '%')
                ->orWhereHas('state', function ($query) use ($request) {
                    $query->where('state', 'LIKE', '%' . $request->location . '%');
                })
                ->get();
        }

        $rooms = $roomQuery->where('listed', Room::LISTED)->get()->makeHidden('images','amenities');
        $hotels = $hotelQuery->get()->makeHidden('images');

        $properties = [
            'rooms' => $rooms,
            'hotels' => $hotels
        ];

        if ($rooms->isNotEmpty() || $hotels->isNotEmpty()) {
            return $this->response(true, 'Properties successfully fetched', $properties, 200);
        }

        return $this->response(false, 'No properties found', [], 404);
    }


    public function showRoom($id) {
        $room = Room::with('hotel')->find($id);
    
        if (!$room) {
            return $this->response(false, 'Room not found', null, 404);
        }
    
        $room->makeHidden(['images']);
        
        if ($room->hotel) {
            $room->hotel->makeHidden(['images']);
        }
    
        return $this->response(true, 'Room detail successfully fetched', $room, 200);
    }
    

}
