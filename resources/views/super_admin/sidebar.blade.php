<div class="side-content-wrap">
    <div class="sidebar-left open rtl-ps-none" data-perfect-scrollbar data-suppress-scroll-x="true">
        <ul class="navigation-left">
            <li class="nav-item {{ isset($mainMenu) && $mainMenu == 'dashboard' ? 'active' : '' }}">
                <a class="nav-item-hold" href="{{ route('superAdmin.dashboard') }}">
                    <i class="nav-icon i-Bar-Chart"></i>
                    <span class="nav-text">{{__('Dashboard')}}</span>
                </a>
                <div class="triangle"></div>
            </li>

            <li class="nav-item {{ isset($mainMenu) && $mainMenu == 'settings' ? 'active' : '' }}">
                <a class="nav-item-hold" href="{{ route('superAdmin.settings') }}">
                    <i class="nav-icon i-Settings-Window"></i>
                    <span class="nav-text">{{ __('Settings') }}</span>
                </a>
                <div class="triangle"></div>
            </li>
        </ul>
    </div>
    <div class="sidebar-overlay"></div>
</div>
