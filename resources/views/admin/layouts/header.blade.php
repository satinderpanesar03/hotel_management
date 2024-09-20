<!DOCTYPE html>
<html class="loading" lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    @include('admin.layouts.app')

    <script>
        @if (Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}";
            var message = "{{ Session::get('message') }}";
            var audio = new Audio('{{ asset('audio.mp3') }}');

            toastr.options.timeOut = 10000;
            toastr[type](message);
            audio.play();
        @endif
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.min.css">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
   

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

</head>

<body class="vertical-layout vertical-menu 2-columns navbar-sticky" data-menu="vertical-menu" data-col="2-columns">



    <nav class="navbar navbar-expand-lg navbar-light header-navbar navbar-fixed">
        <div class="container-fluid navbar-wrapper">
            <div class="navbar-header d-flex">
                <div class="navbar-toggle menu-toggle d-xl-none d-block float-left align-items-center justify-content-center"
                    data-toggle="collapse">
                    <i class="ft-menu font-medium-3"></i>
                </div>
                <ul class="navbar-nav">
                    <li class="nav-item mr-2 d-none d-lg-block">
                        <a class="nav-link apptogglefullscreen" id="navbar-fullscreen" href="javascript:;">
                            <i class="ft-maximize font-medium-3"></i>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="navbar-container">
                <div class="collapse navbar-collapse d-block" id="navbarSupportedContent">
                    <ul class="navbar-nav">
                        <li class="dropdown nav-item mr-1">
                            <a class="nav-link dropdown-toggle user-dropdown d-flex align-items-end" id="dropdownBasic2"
                                href="javascript:;" data-toggle="dropdown">
                                <div class="user d-md-flex d-none mr-2">
                                    <span class="text-right">{{ Auth::user()->name }}</span>
                                </div>
                            </a>
                            <div class="dropdown-menu text-left dropdown-menu-right m-0 pb-0"
                                aria-labelledby="dropdownBasic2">
                                <a class="dropdown-item" href="#">
                                    <div class="d-flex align-items-center"><i class="ft-edit mr-2"></i><span>Edit
                                            Profile</span></div>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">
                                    <div class="d-flex align-items-center"><i
                                            class="ft-power mr-2"></i><span>Logout</span></div>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    @yield('content')
    @include('admin.layouts.sidebar')
    @stack('scripts')


</body>

</html>
