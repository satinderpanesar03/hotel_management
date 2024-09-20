<?php

namespace App\Http\Controllers\admin\hotel;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HotelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Hotel::query();
        $hotels = $query->paginate($request->limit ?? 10);

        return view('admin.hotels.index')->with([
            'hotels' => $hotels
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $states = State::pluck('state', 'id');

        return view('admin.hotels.create')->with([
            'states' => $states
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hotel' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'images' => 'required|array',
            'images.*' => 'file|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        if ($validator->fails()) {
            flash()->error($validator->errors()->first());
            return redirect()->back()->withInput();
        }

        DB::beginTransaction();

        try {
            $hotel = Hotel::create([
                'hotel' => $request->input('hotel'),
                'state_id' => $request->input('state'),
                'city' => $request->input('city'),
            ]);

            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('images', 'public');
                    $imagePaths[] = $path;
                }
            }

            foreach ($imagePaths as $path) {
                $hotel->images()->create(['images' => $path]);
            }

            DB::commit();

            flash()->success('Hotel successfully saved!');
            return redirect()->route('admin.hotels.index');
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('An error occurred while saving the hotel.');
            return redirect()->back()->withInput();
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
        $hotel = Hotel::findOrFail($id);
        $states = State::pluck('state', 'id');

        return view('admin.hotels.create')->with([
            'hotel' => $hotel,
            'states' => $states
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'hotel' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'file|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        if ($validator->fails()) {
            flash()->error($validator->errors()->first());
            return redirect()->back()->withInput();
        }

        DB::beginTransaction();

        try {
            $hotel = Hotel::findOrFail($id);

            $hotel->update([
                'hotel' => $request->input('hotel'),
                'state_id' => $request->input('state'),
                'city' => $request->input('city'),
            ]);

            if ($request->hasFile('images')) {
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('images', 'public');
                    $imagePaths[] = $path;
                }

                foreach ($imagePaths as $path) {
                    $hotel->images()->create(['images' => $path]);
                }
            }

            DB::commit();

            flash()->success('Hotel successfully updated!');
            return redirect()->route('admin.hotels.index');
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error('An error occurred while updating the hotel.');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $hotel = Hotel::find($id);

        if ($hotel) {
            $hotel->delete();
            flash()->success('Hotel successfully deleted!');
        } else {
            flash()->error('Hotel not found.');
        }

        return redirect()->route('admin.hotels.index');
    }
}
