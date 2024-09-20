@extends('admin.layouts.header')

@section('title', 'Rooms')
@section('content')
    <div class="main-panel">
        <div class="main-content">
            <div class="content-overlay"></div>
            <div class="content-wrapper">
                <section id="positioning">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-12 col-sm-7">
                                                <h5 class="pt-2 pb-2">Manage Rooms</h5>
                                            </div>
                                            <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                                <a href="{{ route('admin.rooms.create') }}"
                                                    class="btn btn-sm btn-primary px-3 py-1">
                                                    <i class="fa fa-plus"></i> Add Room </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body table-responsive">
                                        <form action="#" method="get">
                                            <div class="row mb-2" id="listing-filter-data" style="display:none;">
                                                <div class="row col-sm-12 ml-2">
                                                    <div class="col-sm-3">
                                                        <span class="text">Enter </span>
                                                        <input class="form-control" placeholder="Brand Name" type="text"
                                                            id="search" name="type" value="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body table-responsive">
                                                <div><label>Show
                                                        <select name="limit" aria-controls="all_quiz"
                                                            class="form-control-sm" onChange="submit()">
                                                            @foreach (showEntries() as $limit)
                                                                <option value="{{ $limit }}"
                                                                    @if (request()->query('limit') == $limit) selected @endif>
                                                                    {{ $limit }}</option>
                                                            @endforeach
                                                        </select> entries</label></div>
                                        </form>
                                    </div>
                                    <table class="table table-striped table-bordered dom-jQuery-events">
                                        <thead>
                                            <tr>
                                                <th width="5%">ID</th>
                                                <th>Hotel</th>
                                                <th>Room</th>
                                                <th>Price</th>
                                                <th>Room Type</th>
                                                <th>Number of guests</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($rooms as $value => $room)
                                            <tr>
                                                <td>{{ $rooms->firstItem() + $value }}</td>
                                                <td>{{ optional($room->hotel)->hotel ?? '' }}</td>
                                                <td>{{ $room->room ?? '' }}</td>
                                                <td>{{ $room->price ?? '' }}</td>
                                                <td>{{ optional($room->type)->type ?? '' }}</td>
                                                <td>{{ ($room->number_of_guests) }}</td>
                                                <td>@if($room->listed == 1) <span class="btn btn-sm btn-success">Listed</span> @else <span class="btn btn-sm btn-danger">Not Listed</span> @endif</td>
                                                <td class="text-truncate">
                                                    <span style="white-space:nowrap;" class="">
                                                        <x-edit-button :route="route('admin.rooms.edit', $room->id)"></x-edit-button>
                                                        <x-delete-button :route="route('admin.rooms.destroy', $room->id)" />
                                                    </span>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No Rooms</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="container d-flex justify-content-end">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#listing-filter-toggle').click(function() {
                $('#listing-filter-data').toggle();
            });

            $('.delete-color').click(function(event) {
                event.preventDefault();
                var deleteUrl = $(this).attr('href');
                var colorName = $(this).data('color');

                Swal.fire({
                    title: 'Confirm Deletion',
                    text: 'Are you sure you want to delete the color "' + colorName + '"?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = deleteUrl;
                    }
                });
            });
        });
    </script>
@endpush
