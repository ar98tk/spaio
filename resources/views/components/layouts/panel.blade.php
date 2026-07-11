@props(['title' => config('app.name', 'Spaio')])

@php
    use App\Support\TenantPermissions;

    $user = auth()->user();
    $tenant = $user?->tenant;
    $isReceptionist = $user?->role === 'receptionist';
    $items = [
        ['label' => 'Dashboard', 'route' => 'panel.index', 'group' => null, 'hide' => $isReceptionist],
        ['label' => 'Services', 'route' => 'panel.services', 'group' => 'services', 'hide' => false],
        ['label' => 'Packages', 'route' => 'panel.packages', 'group' => 'packages', 'hide' => false],
        ['label' => 'Staff', 'route' => 'panel.staff', 'group' => 'staff', 'hide' => false],
        ['label' => 'Gallery', 'route' => 'panel.gallery', 'group' => 'gallery', 'hide' => false],
        ['label' => 'Templates', 'route' => 'panel.templates', 'group' => 'templates', 'hide' => false],
        ['label' => 'Users', 'route' => 'panel.users', 'group' => 'users', 'hide' => false],
        ['label' => 'Reception', 'route' => 'panel.reception', 'group' => 'reception', 'hide' => false],
        ['label' => 'Bookings', 'route' => 'panel.bookings', 'group' => 'bookings', 'hide' => false],
        ['label' => 'Statistics', 'route' => 'panel.statistics', 'group' => 'analytics', 'hide' => $isReceptionist],
        ['label' => 'Settings', 'route' => 'panel.settings', 'group' => 'settings', 'hide' => false],
        ['label' => 'Billing', 'route' => 'panel.billing', 'group' => 'billing', 'hide' => false],
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="/default.png" type="image/png">
        <title>{{ $title }} | {{ config('app.name', 'Spaio') }}</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700">
        <link href="/metronic/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css">
        <link href="/metronic/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css">
        <link href="/metronic/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css">
        <link href="/metronic/assets/css/style.bundle.css" rel="stylesheet" type="text/css">
        @livewireStyles
    </head>
    <body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="false" class="app-default">
        <script>
            var defaultThemeMode = "light";
            var themeMode;
            if (document.documentElement) {
                if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                    themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
                } else if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }

                if (themeMode === "system") {
                    themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
                }

                document.documentElement.setAttribute("data-bs-theme", themeMode);
            }
        </script>

        <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
            <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
                <div id="kt_app_header" class="app-header">
                    <div class="app-header-logo d-flex align-items-center ps-lg-10 gap-4 gap-lg-6">
                        <button id="kt_app_aside_toggle" class="btn btn-icon bg-gray-300 bg-opacity-75 btn-color-gray-700 btn-active-color-primary rounded-1 w-30px h-30px ms-n3 ms-lg-0" type="button">
                            <i class="ki-outline ki-burger-menu-6 fs-3"></i>
                        </button>

                        <a href="{{ route('panel.index') }}" class="d-flex align-items-center gap-3">
                            <img alt="Spaio" src="/default.png" class="h-25px rounded">
                            <span class="fs-3 fw-bold text-gray-900 d-none d-sm-inline">Spaio</span>
                        </a>
                    </div>

                    <div class="app-header-wrapper">
                        <div class="app-container container-fluid">
                            <div class="app-navbar flex-shrink-0">
                                <div class="app-navbar-item d-flex align-items-stretch flex-lg-grow-1 me-1 me-lg-0">
                                    <div class="header-search d-flex align-items-center w-lg-400px">
                                        <div class="d-none d-lg-block w-100 position-relative mb-5 mb-lg-0">
                                            <i class="ki-outline ki-magnifier search-icon fs-2 text-gray-500 position-absolute top-50 translate-middle-y ms-5"></i>
                                            <input type="text" class="search-input form-control form-control rounded-1 ps-13" value="" placeholder="Search..." disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="app-navbar-item ms-1 ms-lg-3">
                                    <a href="{{ $tenant ? route('tenant.preview', $tenant->subdomain) : route('home') }}" target="_blank" class="btn btn-flex btn-sm fw-bold btn-secondary px-2 px-md-5 py-3">
                                        Preview
                                        <span class="d-none d-sm-inline ps-1">Website</span>
                                    </a>
                                </div>

                                <div class="app-navbar-item ms-1 ms-lg-3">
                                    <div class="cursor-pointer symbol symbol-35px symbol-md-40px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
                                        <span class="symbol-label bg-light-primary text-primary fw-bold">{{ str($user?->name ?? 'U')->substr(0, 1)->upper() }}</span>
                                    </div>

                                    <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px" data-kt-menu="true">
                                        <div class="menu-item px-3">
                                            <div class="menu-content d-flex align-items-center px-3">
                                                <div class="symbol symbol-50px me-5">
                                                    <span class="symbol-label bg-light-primary text-primary fw-bold fs-2">{{ str($user?->name ?? 'U')->substr(0, 1)->upper() }}</span>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <div class="fw-bold d-flex align-items-center fs-5">{{ $user?->name }}</div>
                                                    <span class="fw-semibold text-muted text-hover-primary fs-7">{{ $user?->email }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="separator my-2"></div>
                                        <div class="menu-item px-5">
                                            <a href="{{ route('panel.settings') }}" class="menu-link px-5">Account settings</a>
                                        </div>
                                        <div class="menu-item px-5">
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="menu-link px-5 border-0 bg-transparent w-100 text-start">Sign out</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="app-navbar-item ms-1 ms-lg-3 me-n4 d-flex d-lg-none">
                                    <button id="kt_app_sidebar_mobile_toggle" class="btn btn-icon w-35px h-35px w-md-40px h-md-40px" type="button">
                                        <i class="ki-outline ki-burger-menu-2 fs-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="app-wrapper d-flex" id="kt_app_wrapper">
                    <div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="auto" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
                        <div id="kt_app_sidebar_wrapper" class="app-sidebar-wrapper hover-scroll-y my-2" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_header" data-kt-scroll-offset="5px">
                            <div id="kt_app_sidebar_menu" class="menu menu-sub-indention menu-rounded menu-column fw-semibold fs-6 py-4 py-lg-6 px-2 px-lg-6" data-kt-menu="true">
                                <div class="menu-item">
                                    <div class="menu-content">
                                        <span class="menu-section fs-5 fw-bolder ps-1 py-1">{{ $tenant?->business_name ?? 'Panel' }}</span>
                                    </div>
                                </div>

                                @foreach ($items as $item)
                                    @continue($item['hide'])
                                    @continue($item['group'] && ! TenantPermissions::group($user, $item['group']))

                                    @php
                                        $active = request()->routeIs($item['route']);
                                        $href = Route::has($item['route']) ? route($item['route']) : '#';
                                    @endphp

                                    <div class="menu-item">
                                        <a class="menu-link {{ $active ? 'active' : '' }}" href="{{ $href }}">
                                            <span class="menu-bullet">
                                                <span class="bullet bullet-dot"></span>
                                            </span>
                                            <span class="menu-title">{{ $item['label'] }}</span>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <main class="app-main flex-column flex-row-fluid" id="kt_app_main">
                        <div class="d-flex flex-column flex-column-fluid">
                            <div id="kt_app_content" class="app-content">
                                <div id="kt_app_content_container" class="app-container container-fluid">
                                    <div class="d-flex flex-stack flex-wrap mb-8">
                                        <div class="page-title d-flex flex-column me-3">
                                            <h1 class="d-flex text-gray-900 fw-bold fs-3 my-0">{{ $title }}</h1>
                                            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                                                <li class="breadcrumb-item text-muted">Tenant panel</li>
                                                <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                                                <li class="breadcrumb-item text-muted">{{ $tenant?->business_name }}</li>
                                            </ul>
                                        </div>
                                    </div>

                                    @if (session('success'))
                                        <div class="alert alert-success d-flex align-items-center p-5 mb-6">
                                            <i class="ki-duotone ki-shield-tick fs-2hx text-success me-4"><span class="path1"></span><span class="path2"></span></i>
                                            <div class="fw-semibold">{{ session('success') }}</div>
                                        </div>
                                    @endif

                                    @if (session('error'))
                                        <div class="alert alert-danger d-flex align-items-center p-5 mb-6">
                                            <i class="ki-duotone ki-information-5 fs-2hx text-danger me-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                            <div class="fw-semibold">{{ session('error') }}</div>
                                        </div>
                                    @endif

                                    {{ $slot }}
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>

        <script>var hostUrl = "/metronic/assets/";</script>
        <script src="/metronic/assets/plugins/global/plugins.bundle.js"></script>
        <script src="/metronic/assets/js/scripts.bundle.js"></script>
        <script src="/metronic/assets/js/widgets.bundle.js"></script>
        @livewireScripts
    </body>
</html>
