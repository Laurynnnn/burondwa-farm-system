<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Burondwa Farm Management System</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <style>
            body {
                background: #f6f7f9;
            }
            .sidebar {
                min-height: 100vh;
                background: #006907;
                color: #fff;
                width: 240px;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 1000;
            }
            .sidebar .nav-link, .sidebar .dropdown-item {
                color: #fff;
            }
            .sidebar .nav-link.active, .sidebar .nav-link:hover, .sidebar .dropdown-item:hover {
                background: #388e3c;
                color: #fff;
            }
            .sidebar .nav-link i {
                margin-right: 8px;
            }
            .main-content {
                margin-left: 240px;
                padding: 0;
            }
            .top-navbar {
                height: 60px;
                background: #fff;
                border-bottom: 1px solid #e0e0e0;
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 2rem;
                position: sticky;
                top: 0;
                z-index: 1020;
            }
            .brand {
                font-weight: bold;
                color: #e7f4e7;
                font-size: 1.5rem;
            }
            .dropdown-menu {
                min-width: 180px;
            }
        </style>
        @stack('styles')
    </head>
    <body>
        <div class="sidebar d-flex flex-column p-3">
            <div class="mb-4">
                <span class="brand">Burondwa</span>
            </div>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a class="nav-link dropdown-toggle {{ request()->is('products*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#productsMenu" role="button" aria-expanded="false" aria-controls="productsMenu">
                        <i class="fas fa-box"></i> Products
                    </a>
                    <div class="collapse {{ request()->is('products*') ? 'show' : '' }}" id="productsMenu">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ms-3">
                            <li><a href="{{ route('products.index') }}" class="dropdown-item">All Products</a></li>
                            <li><a href="{{ route('products.create') }}" class="dropdown-item">Add Product</a></li>
                            <li><a href="{{ route('product.categories.index') }}" class="dropdown-item">Categories</a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a class="nav-link dropdown-toggle {{ request()->is('inventory*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#inventoryMenu" role="button" aria-expanded="false" aria-controls="inventoryMenu">
                        <i class="fas fa-warehouse"></i> Inventory
                    </a>
                    <div class="collapse {{ request()->is('inventory*') ? 'show' : '' }}" id="inventoryMenu">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ms-3">
                            <li><a href="{{ route('inventory.index') }}" class="dropdown-item">All Items</a></li>
                            <li><a href="{{ route('inventory.create') }}" class="dropdown-item">Add Item</a></li>
                            <li><a href="{{ route('inventory.categories.index') }}" class="dropdown-item">Categories</a></li>
                            <li><a href="{{ route('inventory.suppliers.index') }}" class="dropdown-item">Suppliers</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <div class="main-content">
            <div class="top-navbar">
                <div></div>
                <div>
                    <span class="me-3">{{ Auth::user()->name ?? 'User' }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link text-danger p-0"><i class="fas fa-sign-out-alt"></i> Logout</button>
                    </form>
                </div>
            </div>
            <main class="py-4 px-4">
                @yield('content')
            </main>
        </div>
        
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        @stack('scripts')
    </body>
</html>
