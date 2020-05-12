<div class="main-header">
    <div class="logo" >
        @if (Auth::user()->role == SUPER_ADMIN_ROLE)
            <a href="{{ route('superAdmin.dashboard') }}"><img style="width: 80px; height: 50px;" src="{{ empty(allSetting('main_logo')) ? asset('assets/images/laravelLogo.png') : asset(logoViewPath() . allSetting('main_logo'))}}" alt=""></a>
        @elseif (Auth::user()->role == ADMIN_ROLE)
            <a href="{{ route('admin.dashboard') }}"><img style="width: 80px; height: 50px;" src="{{ (!isset(Auth::user()->customer->subscriber)) ? asset('assets/images/laravelLogo.png') : asset(logoViewPath() . Auth::user()->customer->subscriber->logo )}}" alt=""></a>
        @endif
    </div>

    <div class="menu-toggle">
        <div></div>
        <div></div>
        <div></div>
    </div>

    <div style="margin: auto"></div>

    <div class="header-part-right">

    <!-- User avatar dropdown -->
        <div class="dropdown">
            <div class="user col align-self-end">
                <img src="{{ asset('assets/images/faces/1.jpg') }}" id="userDropdown" alt="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="{{ route('passwordChange') }}">{{__('Change Password')}}</a>
                    <a class="dropdown-item" href="{{ route('signOut') }}">{{__('Sign out')}}</a>
                </div>
            </div>
        </div>
    </div>

</div>
