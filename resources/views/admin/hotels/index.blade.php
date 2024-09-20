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
                                            <h5 class="pt-2 pb-2">Manage Hotels</h5>
                                        </div>
                                        <div class="col-12 col-sm-5 d-flex justify-content-end align-items-center">
                                            <a href="{{ route('admin.hotels.create') }}" class="btn btn-sm btn-primary px-3 py-1">
                                                <i class="fa fa-plus"></i> Add Hotel </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body table-responsive">
                                    <form action="#" method="get">
                                        <div class="row mb-2" id="listing-filter-data" style="display:none;">
                                            <div class="row col-sm-12 ml-2">
                                                <div class="col-sm-3">
                                                    <span class="text">Enter Brand</span>
                                                    <input class="form-control" placeholder="Brand Name" type="text"
                                                        id="search" name="type" value="">
                                                </div>
                                            </div>
                                            <div class="col-sm-8 mt-2 ml-3">
                                                <div class="row">
                                                    <div class="col-sm-2">
                                                        <button type="submit" class="btn btn-success">Search</button>
                                                    </div>
                                                    <div class="col-sm-2">
                                                        <button value="clear_search" name="clear_search"
                                                            class="btn btn-danger">Clear</button>
                                                    </div>
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
                                                <th>State</th>
                                                <th>City</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($hotels as $value => $hotel)
                                            <tr>
                                                <td>{{ $hotels->firstItem() + $value }}</td>
                                                <td>{{ $hotel->hotel ?? '' }}</td>
                                                <td>{{ optional($hotel->state)->state ?? '' }}</td>
                                                <td>{{ $hotel->city ?? '' }}</td>
                                                <td class="text-truncate">
                                                    <span style="white-space:nowrap;" class="">
                                                        <x-edit-button :route="route('admin.hotels.edit', $hotel->id)"></x-edit-button>
                                                        <x-delete-button :route="route('admin.hotels.destroy', $hotel->id)" />
                                                    </span>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No hotels</td>
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
    });
</script>
@endpush
