@extends('admin.layouts.header')
@section('title', isset($hotel->id) ? 'Edit Hotel' : 'Add Hotel')
@section('content')

    <div class="main-panel">
        <!-- BEGIN : Main Content-->
        <div class="main-content">
            <div class="content-overlay"></div>
            <div class="content-wrapper">
                <section id="simple-validation">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-header" style="background-color: #d6d6d6; color: #000000;  z-index: 1;">
                                        <div class="row">
                                            <div class="col-12 col-sm-7">
                                                <h5 class="pt-2 pb-2">
                                                    @if (isset($room))
                                                        Edit
                                                    @else
                                                        Add
                                                    @endif Room
                                                </h5>
                                            </div>
                                            <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                                <a href="{{ route('admin.rooms.index') }}"
                                                    class="btn btn-sm btn-primary px-3 py-1">
                                                    <i class="fa fa-arrow-left"></i> Back </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <form method="post"
                                                    action="@if (isset($room) && $room->id) {{ route('admin.rooms.update', $room->id) }} @else {{ route('admin.rooms.store') }} @endif"
                                                    enctype="multipart/form-data">

                                                    @csrf
                                                    @if (isset($room) && $room->id)
                                                        @method('PUT')
                                                    @endif
                                                    <input type="hidden" name="id" value="{{ $room->id ?? null }}">

                                                    <div class="form-group">
                                                        <label for="Hotel">Select Hotel <strong
                                                                class="text-danger">*</strong>
                                                        </label>
                                                        <select name="hotel" id="hotel" class="form-control" required>
                                                            <option value="" selected disabled>Choose...</option>
                                                            @foreach ($hotels as $value => $hotel)
                                                                <option value="{{ $value }}"
                                                                    @if (isset($room) && $room->hotel_id == $value) selected @endif>
                                                                    {{ $hotel }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="Hotel">Room Name <strong
                                                                class="text-danger">*</strong>
                                                        </label>
                                                        <input type="text" value="{{ old('room', $room->room ?? '') }}"
                                                            name="room" class="form-control"
                                                            placeholder="Enter room name" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="room_type_id">Room Type <strong
                                                                class="text-danger">*</strong>
                                                        </label>
                                                        <select name="room_type_id" id="room_type_id" class="form-control"
                                                            required>
                                                            <option value="" selected disabled>Choose...</option>
                                                            @foreach ($room_types as $value => $type)
                                                                <option value="{{ $value }}"
                                                                    @if (isset($room) && $room->room_type_id == $value) selected @endif>
                                                                    {{ $type }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="Hotel">Select Amenities <strong
                                                                class="text-danger">*</strong>
                                                        </label>
                                                        <select name="amenities[]" id="amenities" multiple required>
                                                            <option value="" selected disabled>Choose...</option>
                                                            @foreach ($amenities as $value => $amenity)
                                                                <option value="{{ $value }}"
                                                                    @if (isset($room) && in_array($value, $room->amenities->pluck('id')->toArray())) selected @endif>
                                                                    {{ $amenity }}</option>
                                                            @endforeach
                                                        </select>

                                                    </div>


                                                    <div class="form-group">
                                                        <label for="Hotel">Number of Guests <strong
                                                                class="text-danger">*</strong>
                                                        </label>
                                                        <input type="number"
                                                            value="{{ old('number_of_guests', $room->number_of_guests ?? '') }}"
                                                            name="number_of_guests" class="form-control"
                                                            placeholder="Enter number of guests" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="Hotel">Price per night <strong
                                                                class="text-danger">*</strong>
                                                        </label>
                                                        <input type="number"
                                                            value="{{ old('price', $room->price ?? '') }}" name="price"
                                                            class="form-control" placeholder="Enter price" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="status">Make it Listed ? </label>
                                                        <input type="checkbox" id="status" name="status"
                                                            @if (isset($room) && $room->listed == 1) checked @endif
                                                            data-toggle="toggle" data-onstyle="success"
                                                            data-offstyle="secondary" data-size="sm">
                                                    </div>


                                                    <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="Hotel">Upload Images <strong
                                                            class="text-danger">*</strong></label>
                                                    <input type="file" name="images[]"class="form-control" multiple>
                                                </div>

                                                @if (isset($room))
                                                    <div class="row">
                                                        @foreach ($room->images as $image)
                                                            <div class="col-md-4">
                                                                <div class="card">
                                                                    <img src="{{ asset('storage/' . $image->path) }}"
                                                                        class="card-img-top" alt="...">
                                                                    <div class="text-center">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                            </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#amenities').selectize();
        });


        function confirmDelete(imageId) {
            if (confirm('Are you sure you want to delete this image?')) {
                var form = document.getElementById('delete-form-' + imageId);
                if (form) {
                    form.submit();
                }
            }
        }
    </script>
@endpush
