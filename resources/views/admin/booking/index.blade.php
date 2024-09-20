@extends('admin.layouts.header')

@section('title', 'Hotels')
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
                                                <h5 class="pt-2 pb-2">Manage Bookings</h5>
                                            </div>
                                            <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body table-responsive">
                                        <form action="#" method="get">
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
                                                <th>User</th>
                                                <th>Email</th>
                                                <th>Hotel</th>
                                                <th>Location</th>
                                                <th>Room</th>
                                                <th>Type</th>
                                                <th>Check In</th>
                                                <th>Check Out</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($bookings as $value => $booking)
                                                <tr>
                                                    <td>{{ $bookings->firstItem() + $value }}</td>
                                                    <td>{{ optional($booking->user)->name ?? '' }}</td>
                                                    <td>{{ optional($booking->user)->email ?? '' }}</td>
                                                    <td>{{ optional($booking->room)->hotel->hotel ?? '' }}</td>
                                                    <td>{{ optional($booking->room)->hotel->state->state ?? '' }}</td>
                                                    <td>{{ optional($booking->room)->room ?? '' }}</td>
                                                    <td>{{ optional($booking->room)->type->type ?? '' }}</td>
                                                    <td>{{ $booking->check_in ?? '' }}</td>
                                                    <td>{{ $booking->check_out ?? '' }}</td>
                                                    <td>${{ $booking->price ?? '' }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center">No bookings</td>
                                                </tr>
                                            @endforelse

                                        </tbody>
                                    </table>
                                    <div class="container d-flex justify-content-end">
                                        {{ $bookings->appends($_GET)->links() }}
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
