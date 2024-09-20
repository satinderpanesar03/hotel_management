@extends('admin.layouts.header')

@section('title', 'Dashboard')

@section('content')
    <!-- Main Content Area -->
    <div class="main-panel">
        <div class="main-content">
            <div class="content-wrapper">
                <div class="row match-height">
                    <div class="col-xl-12 col-lg-12 col-12">
                        <div class="card shopping-cart">
                            <div class="card-header pb-2">
                                <h3 class="card-title mb-0">
                                    Welcome <b>{{ Auth::user()->name ?? 'Guest' }}</b>
                                </h3>
                            </div>
                            <div class="card-body">
                                <p>Dashboard content is currently under construction.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        console.log("Dashboard page script running.");
    </script>
@endpush
