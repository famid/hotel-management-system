<!DOCTYPE html>
<html lang="en" dir="">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,400i,600,700,800,900" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/styles/css/themes/lite-purple.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/styles/vendor/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/styles/vendor/dropzone.min.css') }}">
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.datetimepicker.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/font-awesome.min.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/jquery.dataTables.min.css') }}"/>
    <script src="{{ asset('assets/js/jquery.datetimepicker.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-confirm.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}"/>
    @yield('style')

</head>

<body class="text-left">
<div class="app-admin-wrap layout-sidebar-large">
    @include('layouts.header')
    @if (Auth::user()->role == SUPER_ADMIN_ROLE)
        @include('super_admin.sidebar')
    @elseif (Auth::user()->role == ADMIN_ROLE)
        @include('admin.sidebar')
    @endif
    <div class="main-content-wrap sidenav-open d-flex flex-column">
        <div class="breadcrumb">
            @if(isset($locationName))
                <h1>{{ isset($menuName) ? menuName($menuName) . ' - '. $locationName : '' }}</h1>
            @else
                <h1>{{ isset($menuName) ? menuName($menuName) : '' }}</h1>
            @endif
            <ul>
                @if (Auth::user()->role == SUPER_ADMIN_ROLE)
                    <li><a href="{{ route('superAdmin.dashboard') }}">{{__('Home')}}</a></li>
                @elseif (Auth::user()->role == ADMIN_ROLE)
                    <li><a href="{{ route('admin.dashboard') }}">{{__('Home')}}</a></li>
                @endif
                <li>{{ isset($subMenuName) ? menuName($subMenuName) : menuName($menuName) }}</li>
            </ul>
        </div>

        <div class="separator-breadcrumb border-top"></div>
        <div class="alert-float alert" id="alert" style="display: none">
            <button type="button" class="close" id="alertRemove" aria-hidden="true">x</button>
            <span class="message"></span>
        </div>
        @if(Session::has('success'))
            <div class="alert-float alert alert-success alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                {{Session::get('success')}}
            </div>
        @endif

        @if(Session::has('error'))
            <div class="alert-float alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                {{Session::get('error')}}
            </div>
        @endif
        @if(!empty($errors) && count($errors) > 0)
            <div class="alert-float alert alert-danger alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                {{$errors->first()}}
            </div>
        @endif
        @yield('content')
    </div>
</div>
<!-- ============ Search UI End ============= -->


<script src="{{ asset('assets/js/vendor/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/perfect-scrollbar.min.js') }}"></script>
<script src="{{asset('assets/js/vendor/dropzone.min.js')}}"></script>

<script src="{{ asset('assets/js/es5/script.min.js') }}"></script>
<script src="{{ asset('assets/js/es5/sidebar.large.script.js') }}"></script>

{{--<script src="{{ asset('assets/js/form.basic.script.js') }}"></script>--}}
<script src="{{asset('assets/js/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.bootstrap.js')}}"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
<script src="{{ asset('assets/js/jquery-confirm.min.js') }}"></script>

{{--<script src="{{asset('assets/js/dropzone.script.js')}}"></script>--}}
@yield('script')
<script>
    $(document).on("click", ".confirmedDelete", function() {
        var link = $(this).data("link");
        jQuery.confirm({
            content: '{{ __('Do you really want to delete?') }}',
            title: '{{ __('Confirmation') }}',
            buttons: {
                confirm: {
                    text: '{{ __('Yes') }}',
                    keys: ['enter'],
                    action: function(){
                        window.location.replace(link);
                    }
                },
                cancel: {
                    text: '{{ __('No') }}',
                    keys: ['esc'],
                    action: function(){
                    }
                }
            }
        });
    });
    $('#alertRemove').click(function () {
        $('#alert').hide();
    });
</script>
</body>

</html>
