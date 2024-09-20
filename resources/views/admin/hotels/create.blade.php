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
                                                    @if (isset($type->id))
                                                        Edit
                                                    @else
                                                        Add
                                                    @endif Hotel
                                                </h5>
                                            </div>
                                            <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                                <a href="{{ route('admin.hotels.index') }}"
                                                    class="btn btn-sm btn-primary px-3 py-1">
                                                    <i class="fa fa-arrow-left"></i> Back </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <form method="post"
                                                    action="@if (isset($hotel) && $hotel->id) {{ route('admin.hotels.update', $hotel->id) }} @else {{ route('admin.hotels.store') }} @endif"
                                                    enctype="multipart/form-data">

                                                    @csrf
                                                    @if (isset($hotel) && $hotel->id)
                                                        @method('PUT')
                                                    @endif
                                                    <input type="hidden" name="id" value="{{ $hotel->id ?? null }}">

                                                    <div class="form-group">
                                                        <label for="Hotel">Hotel <strong class="text-danger">*</strong>
                                                        </label>
                                                        <input type="text" name="hotel" class="form-control"
                                                            placeholder="Hotel Name"
                                                            value="{{ old('hotel', $hotel->hotel ?? '') }}">
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="Hotel">Select State <strong
                                                                class="text-danger">*</strong></label>
                                                        <select id="state" name="state" class="form-control">
                                                            <option value="">Select State</option>
                                                            @foreach ($states as $value => $state)
                                                                <option value="{{ $value }}"
                                                                    @if (isset($hotel) && $value == $hotel->state_id) selected @endif>
                                                                    {{ $state }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="Hotel">Enter City <strong
                                                                class="text-danger">*</strong> </label>
                                                        <input type="text" name="city" class="form-control"
                                                            placeholder="City"
                                                            value="{{ old('city', $hotel->city ?? '') }}">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="form-group">
                                                    <label for="Hotel">Upload Images <strong
                                                            class="text-danger">*</strong></label>
                                                    <input type="file" name="images[]"class="form-control" multiple>
                                                </div>
                                                
                                                


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

