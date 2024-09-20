<?php

namespace App\Http\Controllers\admin\room;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\RoomImage;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Room::query();
        $rooms = $query->paginate($request->limit ?? 10);

        return view('admin.rooms.index')->with([
            'rooms' => $rooms
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hotels = Hotel::pluck('hotel', 'id');
        $amenities = Amenity::pluck('amenity', 'id');
        $room_types = RoomType::pluck('type', 'id');

        return view('admin.rooms.create')->with([
            'hotels' => $hotels,
            'amenities' => $amenities,
            'room_types' => $room_types
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hotel' => 'required',
            'price' => 'required',
            'room' => 'required|string|max:255',
            'room_type_id' => 'required',
            'amenities' => 'required|array',
            'amenities.*' => 'exists:amenities,id',
            'number_of_guests' => 'required|integer|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            flash()->error($validator->errors()->first());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $validated = $validator->validated();

            $room = Room::create([
                'hotel_id' => $validated['hotel'],
                'room' => $validated['room'],
                'room_type_id' => $validated['room_type_id'],
                'number_of_guests' => $validated['number_of_guests'],
                'price' => $validated['price'],
                'listed' => $request->has('status'),
            ]);

            $room->amenities()->attach($validated['amenities']);

            if (isset($validated['images'])) {
                foreach ($validated['images'] as $image) {
                    $path = $image->store('images', 'public');
                    $room->images()->create(['path' => str_replace('public/', '', $path)]);
                }
            }

            DB::commit();

            flash()->success('Room successfully added');
            return redirect()->route('admin.rooms.index')->with('success', 'Room created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            flash()->error('Something went wrong');
            return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $room = Room::findOrFail($id);
        $hotels = Hotel::pluck('hotel', 'id');
        $amenities = Amenity::pluck('amenity', 'id');
        $room_types = RoomType::pluck('type', 'id');

        return view('admin.rooms.create')->with([
            'hotels' => $hotels,
            'amenities' => $amenities,
            'room_types' => $room_types,
            'room' => $room
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'hotel' => 'required',
            'price' => 'required',
            'room' => 'required|string|max:255',
            'room_type_id' => 'required|exists:room_types,id',
            'amenities' => 'required|array',
            'amenities.*' => 'exists:amenities,id',
            'number_of_guests' => 'required|integer|min:1',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            flash()->error($validator->errors()->first());
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $validated = $validator->validated();

            $room = Room::findOrFail($id);
            $room->update([
                'hotel_id' => $validated['hotel'],
                'room' => $validated['room'],
                'room_type_id' => $validated['room_type_id'],
                'number_of_guests' => $validated['number_of_guests'],
                'price' => $validated['price'],
                'listed' => $request->has('status'),
            ]);

            $room->amenities()->sync($validated['amenities']);

            if (isset($validated['images'])) {
                foreach ($validated['images'] as $image) {
                    $path = $image->store('images', 'public');
                    $room->images()->create(['path' => str_replace('public/', '', $path)]);
                }
            }

            DB::commit();

            flash()->success('Room successfully updated');
            return redirect()->route('admin.rooms.index')->with('success', 'Room updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            flash()->error('Something went wrong');
            return redirect()->back()->withErrors(['error' => 'Something went wrong.']);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $room = Room::find($id);

        if ($room) {
            foreach ($room->images as $image) {
                $imagePath = storage_path('app/public/' . $image->path);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
                $image->delete();
            }

            $room->delete();
            flash()->success('Room successfully deleted!');
        } else {
            flash()->error('Room not found.');
        }

        return redirect()->route('admin.rooms.index');
    }

    public function deleteImage($id)
    {
        $image = RoomImage::findOrFail($id);
        $imagePath = storage_path('app/public/' . $image->path);

        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        $image->delete();

        flash()->success('Image successfully deleted!');
        return redirect()->back();
    }
}
