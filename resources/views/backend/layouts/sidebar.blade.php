<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        {{-- <h4 class="text-white"> {{config('app.name')}} </h4> --}}
        <!-- Dark Logo-->
        {{-- @if ($setting != null)
            
            <!-- Light Logo-->
            <a href="#" class="logo logo-light">
                <span class="logo-sm">
                    <img src="{{ URL::asset($setting->getFirstMediaUrl('logo_header')) }}" alt="" height="22">
                </span>
                <span class="logo-lg">
                    <img src="{{ URL::asset($setting->getFirstMediaUrl('logo_header')) }}" alt="" width="50"
                        class="rounded-circle">
                </span>
            </a>
        @endif --}}

        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    {{-- @auth
        <div class="dropdown sidebar-user m-1 rounded">
            <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <span class="d-flex align-items-center gap-2">
                    <img class="rounded header-profile-user"
                        src="@if (Auth::user()->avatar != '') {{ URL::asset('images/' . Auth::user()->avatar) }}@else{{ URL::asset('build/images/users/avatar-1.jpg') }} @endif"
                        alt="Header Avatar">
                    <span class="text-start">
                        <span class="d-block fw-medium sidebar-user-name-text">{{ Auth::user()->username }}</span>
                        <span class="d-block fs-14 sidebar-user-name-sub-text"><i
                                class="ri ri-circle-fill fs-10 text-success align-baseline"></i> <span
                                class="align-middle">En ligne</span></span>
                    </span>
                </span>
            </button>
            <div class="dropdown-menu dropdown-menu-end">
                <!-- item-->
                <h6 class="dropdown-header">Bienvenue {{ Auth::user()->username }}!</h6>
                <a class="dropdown-item" href="{{ route('admin-register.profil', Auth::user()->id) }}"><i
                        class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                        class="align-middle">Profil</span></a>

                <a class="dropdown-item" href="#"><i class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i>
                    <span class="align-middle">Aide</span></a>
                <div class="dropdown-divider"></div>



                <a class="dropdown-item " href="javascript:void();"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                        class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span
                        key="t-logout">@lang('translation.logout')</span></a>
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    @endauth --}}

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            {{-- @if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'developpeur')
            @endif --}}
            <ul class="navbar-nav" id="navbar-nav">

                @can('voir-tableau de bord')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Route::is('dashboard.*') ? 'active' : '' }} "
                            href="{{ route('dashboard.index') }}">
                            <i class="ri-home-4-line"></i> <span>TABLEAU DE BORD</span>
                        </a>
                    </li>
                @endcan

                @can('voir-categorie')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Route::is('categorie.*') ? 'active' : '' }} "
                            href="{{ route('categorie.index') }}">
                            <i class="ri-folder-3-line"></i> <span>CATEGORIES</span>
                        </a>
                    </li>
                @endcan

                @can('voir-matiere')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Route::is('matiere.*') ? 'active' : '' }} "
                            href="{{ route('matiere.index') }}">
                            <i class="ri-book-2-line"></i> <span>MATIERES</span>
                        </a>
                    </li>
                @endcan

                @can('voir-niveau')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Route::is('niveau.*') ? 'active' : '' }} "
                            href="{{ route('niveau.create') }}">
                            <i class="ri-bar-chart-grouped-line"></i> <span>NIVEAUX</span>
                        </a>
                    </li>
                @endcan

                @can('voir-sujet')
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Route::is('sujet.*') ? 'active' : '' }} "
                            href="{{ route('sujet.index') }}">
                            <i class="ri-file-list-3-line"></i> <span>SUJETS</span>
                        </a>
                    </li>
                @endcan


                   {{-- @can('voir-sujet') --}}
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Route::is('slider.*') ? 'active' : '' }} "
                            href="{{ route('slider.index') }}">
                            <i class="ri-file-list-3-line"></i> <span>SLIDER</span>
                        </a>
                    </li>
                {{-- @endcan --}}

                @if (Auth::user()->role == 'superadmin' || Auth::user()->role == 'developpeur' || Auth::user()->can('voir-parametre'))
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarAuth" data-bs-toggle="collapse" role="button"
                            aria-controls="sidebarAuth">
                            <i class="ri-settings-3-line"></i> <span>PARAMETRE</span>
                        </a>
                        <div class="collapse menu-dropdown {{ Route::is('role.*') || Route::is('parametre.*') || Route::is('module.*') || Route::is('role.*') || Route::is('permission.*') || Route::is('admin-register.*') ? 'show' : '' }}"
                            id="sidebarAuth">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item active">
                                    <a href="{{ route('parametre.index') }}"
                                        class="nav-link {{ Route::is('parametre.*') ? 'active' : '' }}">
                                        <i class="ri-information-line"></i> Informations
                                    </a>
                                </li>
                                <li class="nav-item active">
                                    <a href="{{ route('admin-register.index') }}"
                                        class="nav-link {{ Route::is('admin-register.*') ? 'active' : '' }}">
                                        <i class="ri-user-settings-line"></i> Utilisateurs
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('module.index') }}"
                                        class="nav-link {{ Route::is('module.*') ? 'active' : '' }}">
                                        <i class="ri-apps-line"></i> Modules
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('role.index') }}"
                                        class="nav-link {{ Route::is('role.*') ? 'active' : '' }}">
                                        <i class="ri-shield-user-line"></i> Roles
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('permission.index') }}"
                                        class="nav-link {{ Route::is('permission.*') ? 'active' : '' }}">
                                        <i class="ri-lock-2-line"></i> Permissions/Roles
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
