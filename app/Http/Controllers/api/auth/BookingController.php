<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiResponse;
use Illuminate\Container\Attributes\Auth;

class BookingController extends Controller
{
    use ApiResponse;

    public function storeBooking(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->response(false, $validator->errors()->first(), [], 422);
        }

        $bookingStatus = Booking::where('room_id', $request->room_id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('check_in', [$request->check_in, $request->check_out])
                    ->orWhereBetween('check_out', [$request->check_in, $request->check_out])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('check_in', '<=', $request->check_in)
                            ->where('check_out', '>=', $request->check_out);
                    });
            })
            ->exists();

        if ($bookingStatus) {
            return $this->response(false, 'The room is already booked for the selected dates.', [], 409);
        }

        try {
            $booking = Booking::create([
                'room_id' => $request->room_id,
                'user_id' => $request->user()->id,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'price' => $request->price,
            ]);

            return $this->response(true, 'Room successfully booked', [$booking], 200);
        } catch (\Exception $e) {
            return $this->response(false, 'An error occurred while booking the room. Please try again later.', [], 500);
        }
    }

    public function viewBookings(Request $request)
    {
        try {
            $bookings = Booking::with(['room.hotel', 'user','room.type'])
                ->where('user_id', $request->user()->id)
                ->orderBy('id','desc')
                ->get()
                ->map(function ($booking) {
                    return [
                        'id' => $booking->id ?? null,
                        'user_id' => $booking->user->id ?? null,
                        'user' => $booking->user->name ?? null,
                        'user' => $booking->user->email ?? null,
                        'room' => $booking->room->room ?? null,
                        'room_type' => $booking->room->type->type ?? null,
                        'hotel' => $booking->room->hotel->hotel ?? null,
                        'city' => $booking->room->hotel->city ?? null,
                        'city' => $booking->room->hotel->city ?? null,
                        'number_of_guests' => $booking->room->number_of_guests ?? null,
                        'price' => $booking->price ?? null,
                        'check_in' => $booking->check_in ?? null,
                        'check_out' => $booking->check_out ?? null,
                        'amenities' => $booking->room ? $booking->room->amenities->pluck('amenity') : [],
                    ];
                });

            return $this->response(true, 'Bookings successfully fetched', $bookings, 200);
        } catch (\Exception $e) {
            return $this->response(false, 'Failed to fetch bookings: ' . $e->getMessage(), [], 500);
        }
    }
}
