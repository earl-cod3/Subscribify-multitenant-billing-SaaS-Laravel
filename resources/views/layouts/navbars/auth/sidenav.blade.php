<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-2
    {{ $bg ?? ''}} {{ $box ?? ''}}"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0"
            href="{{ route('dashboards', ['page' => 'default']) }}">
            <img src="{{ $logo ?? '/assets/img/logo-ct-dark.png'}}" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-1 font-weight-bold">Multitenant billing SaaS Laravel</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#dashboardsExamples" class="nav-link {{ Route::currentRouteName() == 'dashboards' ? 'active' : '' }}"
                    aria-controls="dashboardsExamples" role="button" aria-expanded="false">
                    <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-shop text-primary text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboards</span>
                </a>
                <div class="collapse {{ Route::currentRouteName() == 'dashboards' ? 'show' : '' }} " id="dashboardsExamples">
                    <ul class="nav ms-4">
                        <li class="nav-item ">
                            <a class="nav-link {{ str_contains(request()->url(), 'landing') == true ? 'active' : '' }}" href="{{ route('dashboards', ['page' => 'landing']) }}">
                                <span class="sidenav-mini-icon"> L </span>
                                <span class="sidenav-normal"> Landing </span>
                            </a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link {{ str_contains(request()->url(), 'default') == true ? 'active' : '' }}" href="{{ route('dashboards', ['page' => 'default']) }}">
                                <span class="sidenav-mini-icon"> D </span>
                                <span class="sidenav-normal"> Default </span>
                            </a>
                        </li>


                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#laravelExamples" class="nav-link active"
                    aria-controls="laravelExamples" role="button" aria-expanded="false">
                    <div class="icon icon-shape icon-sm text-center d-flex align-items-end justify-content-center">
                        <i class="fab fa-laravel" style="color: #f4645f; font-size: large; font-weight: 500 "></i>
                    </div>
                    <span class="nav-link-text ms-1">Laravel RBAC </span>
                </a>
                <div class="collapse show" id="laravelExamples">
                    <ul class="nav ms-4">
                        <li class="nav-item ">
                            <a class="nav-link {{ Route::currentRouteName() == 'user-profile' ? 'active' : '' }}" href="{{ route('user-profile') }}">
                                <span class="sidenav-mini-icon"> U </span>
                                <span class="sidenav-normal"> User Profile </span>
                            </a>
                        </li>
                        @can('manage-users', auth()->user())
                            <li class="nav-item ">
                                <a class="nav-link {{ Route::currentRouteName() == 'user-management' ? 'active' : '' }}" href="{{ route('user-management') }}">
                                    <span class="sidenav-mini-icon"> U </span>
                                    <span class="sidenav-normal"> User Management </span>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link {{ Route::currentRouteName() == 'role-management' ? 'active' : '' }}" href="{{ route('role-management') }}">
                                    <span class="sidenav-mini-icon"> R </span>
                                    <span class="sidenav-normal"> Role Management </span>
                                </a>
                            </li>
                        @endcan
                        @can('manage-items', auth()->user())
                            <li class="nav-item ">
                                <a class="nav-link {{ Route::currentRouteName() == 'category-management' ? 'active' : '' }}" href="{{ route('category-management') }}">
                                    <span class="sidenav-mini-icon"> C </span>
                                    <span class="sidenav-normal"> Category Management </span>
                                </a>
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link {{ Route::currentRouteName() == 'tag-management' ? 'active' : '' }}" href="{{ route('tag-management') }}">
                                    <span class="sidenav-mini-icon"> T </span>
                                    <span class="sidenav-normal"> Tag Management </span>
                                </a>
                            </li>
                        @endcan
                        <li class="nav-item ">
                            <a class="nav-link {{ Route::currentRouteName() == 'item-management' ? 'active' : '' }}" href="{{ route('item-management') }}">
                                <span class="sidenav-mini-icon"> I </span>
                                <span class="sidenav-normal"> Items Management </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item mt-3">
                <h6 class="ps-4  ms-2 text-uppercase text-xs font-weight-bolder opacity-6">PAGES</h6>
            </li>
            <li class="nav-item">
                <a data-bs-toggle="collapse" href="#pagesExamples" class="nav-link {{ str_contains(request()->url(), 'pages') == true ? 'active' : '' }}"
                    aria-controls="pagesExamples" role="button" aria-expanded="false">
                    <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                        <i class="ni ni-ungroup text-warning text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Billing</span>
                </a>
                <div class="collapse {{ str_contains(request()->url(), 'pages') == true ? 'show' : '' }}" id="pagesExamples">
                    <ul class="nav ms-4">

                        <li class="nav-item ">
                            <a class="nav-link {{ Route::currentRouteName() == 'accounts' ? 'active' : '' }}" data-bs-toggle="collapse" aria-expanded="false"
                                href="#accountExample">
                                <span class="sidenav-mini-icon"> A </span>
                                <span class="sidenav-normal"> Account <b class="caret"></b></span>
                            </a>
                            <div class="collapse {{ Route::currentRouteName() == 'accounts' ? 'show' : '' }}" id="accountExample">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link {{ str_contains(request()->url(), 'billing') == true ? 'active' : '' }}" href="{{ route('accounts', ['page' => 'billing']) }}">
                                            <span class="sidenav-mini-icon text-xs"> B </span>
                                            <span class="sidenav-normal"> Billing </span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ str_contains(request()->url(), 'invoice') == true ? 'active' : '' }}" href="{{ route('accounts', ['page' => 'invoice']) }}">
                                            <span class="sidenav-mini-icon text-xs"> I </span>
                                            <span class="sidenav-normal"> Invoice </span>
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </li>

                    </ul>
                </div>
            </li>

        </ul>
    </div>

</aside>
