<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>MyShop</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="{{ asset('vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/select2/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/vertical-layout-light/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<style>
    .content-wrapper{
        padding: 1rem 1rem;
    }
    .card-header:first-child{
        background-color: #c5d6ff;
    }
    .card .card-body {
        padding: 0.8rem 0.8rem;
    }
</style>
<body>
<div class="container-scroller">
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
            <a class="navbar-brand brand-logo mr-5" href="{{ url('dashboard') }}">
                <img src="{{ asset('images/logo.png') }}" height="auto" width="70px" class="mr-2" alt="logo"/>
            </a>
            <a class="navbar-brand brand-logo-mini" href="{{ url('dashboard') }}">
                <img src="{{ asset('images/logo.png') }}" alt="logo"/>
            </a>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
            <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
                <span class="icon-menu"></span>
            </button>
            <ul class="navbar-nav navbar-nav-right">
                <li class="nav-item dropdown">
                    <a class="nav-link" style="font-weight: bold;color: black" href="{{ url('account/settings') }}"><i class="fa fa-gear"></i> Account Settings</a>
                    <a class="nav-link count-indicator" href="{{ url('logout') }}">
                        <i class="fas fa-sign-out ml-3 font-weight-bold"></i>
                    </a>
                </li>
            </ul>
            <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                <span class="icon-menu"></span>
            </button>
        </div>
    </nav>
    <div class="container-fluid page-body-wrapper">
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('dashboard') }}">
                        <i class="icon-grid menu-icon"></i>
                        <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                @if (Auth::user()->admin_type != 2)
                    <li class="nav-item" id="settings">
                        <a class="nav-link" href="{{ url('system/settings') }}">
                            <i class="fa fa-cog menu-icon"></i>
                            <span class="menu-title">Settings</span>
                        </a>
                    </li>
                    <li class="nav-item" id="userstx">
                        <a class="nav-link" href="{{ url('user') }}">
                            <i class="fa fa-user menu-icon"></i>
                            <span class="menu-title">Users</span>
                        </a>
                    </li>
                @endif
                <li class="nav-item" id="categories">
                    <a class="nav-link" href="{{ url('categories') }}">
                        <i class="fa fa-archive menu-icon"></i>
                        <span class="menu-title">Categories</span>
                    </a>
                </li>
                <li class="nav-item" id="companies">
                    <a class="nav-link" href="{{ url('companies') }}">
                        <i class="fa fa-building menu-icon"></i>
                        <span class="menu-title">Companies</span>
                    </a>
                </li>
                <li class="nav-item" id="achead">
                    <a class="nav-link" href="{{ url('achead') }}">
                        <i class="fa fa-credit-card menu-icon"></i>
                        <span class="menu-title">Expense Head</span>
                    </a>
                </li>
                <li class="nav-item" id="items">
                    <a class="nav-link" href="{{ url('item') }}">
                        <i class="fa fa-list-alt menu-icon"></i>
                        <span class="menu-title">Items</span>
                    </a>
                </li>
                <li class="nav-item" id="sales_corner">
                    <a class="nav-link" href="{{ url('pos') }}">
                        <i class="fa fa-cart-plus menu-icon"></i>
                        <span class="menu-title">Sales Corner</span>
                    </a>
                </li>
                <li class="nav-item" id="customers">
                    <a class="nav-link" href="{{ url('customer') }}">
                        <i class="fa fa-user-secret menu-icon"></i>
                        <span class="menu-title">Customers</span>
                    </a>
                </li>
                <li class="nav-item" id="expenses">
                    <a class="nav-link" href="{{ url('expense') }}">
                        <i class="fa fa-bullseye menu-icon"></i>
                        <span class="menu-title">Expenses</span>
                    </a>
                </li>
                @if (Auth::user()->admin_type != 2)
                    <li class="nav-item" id="reports">
                        <a class="nav-link" href="{{ url('report') }}">
                            <i class="fa fa-bars menu-icon"></i>
                            <span class="menu-title">Reports</span>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link" href="{{ url('logout') }}">
                        <i class="fa fa-sign-out menu-icon"></i>
                        <span class="menu-title">Logout</span>
                    </a>
                </li>

            </ul>
        </nav>
        @yield('content')
    </div>
</div>
<script src="{{ asset('vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('vendors/select2/select2.min.js') }}"></script>
<script src="{{ asset('js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('js/template.js') }}"></script>
<script src="{{ asset('js/off-canvas.js') }}"></script>
<script src="{{ asset('js/select2.js') }}"></script>
<script src="{{ asset('js/settings.js') }}"></script>
<script src="{{ asset('js/todolist.js') }}"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>
