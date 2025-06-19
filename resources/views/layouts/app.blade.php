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
            :root {
                --primary-green: #328033;
                --light-green: #6bbc70;
                --pale-green: #d6e2dc;
                --dark-green: #233821;
                --gray: #87919c;
            }

            body {
                background: var(--pale-green);
                font-family: 'Figtree', sans-serif;
            }

            .sidebar {
                min-height: 100vh;
                background: var(--primary-green);
                color: #fff;
                width: 240px;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 1000;
                transition: all 0.3s ease;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            }

            .sidebar .nav-link, .sidebar .dropdown-item {
                color: #fff;
                padding: 0.8rem 1rem;
                border-radius: 8px;
                margin: 0.2rem 0;
                transition: all 0.3s ease;
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .sidebar .nav-link.active, 
            .sidebar .nav-link:hover, 
            .sidebar .dropdown-item:hover {
                background: var(--light-green);
                color: #fff;
                transform: translateX(5px);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            }

            .sidebar .nav-link i {
                width: 20px;
                height: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
                font-size: 1.1rem;
            }

            .sidebar .nav-link:hover i,
            .sidebar .dropdown-item:hover i {
                transform: scale(1.2);
                color: #fff;
            }

            /* Sub-menu specific styles */
            .btn-toggle-nav .dropdown-item {
                padding-left: 2.5rem;
                font-size: 0.95rem;
                opacity: 0.9;
            }

            .btn-toggle-nav .dropdown-item:hover {
                opacity: 1;
                background: var(--light-green);
            }

            .btn-toggle-nav .dropdown-item i {
                font-size: 0.9rem;
            }

            .main-content {
                margin-left: 240px;
                padding: 0;
                background: #fff;
                min-height: 100vh;
                transition: all 0.3s ease;
            }

            .top-navbar {
                height: 70px;
                background: #fff;
                border-bottom: 1px solid var(--pale-green);
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 2rem;
                position: sticky;
                top: 0;
                z-index: 1020;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            }

            .top-navbar .user-section {
                display: flex;
                align-items: center;
                gap: 1.5rem;
                background: var(--pale-green);
                padding: 0.5rem 1rem;
                border-radius: 8px;
            }

            .top-navbar .user-section .user-name {
                color: var(--dark-green);
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .top-navbar .user-section .user-name i {
                color: var(--primary-green);
            }

            .top-navbar .user-section .logout-btn {
                color: var(--primary-green);
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                transition: all 0.2s ease;
                padding: 0.4rem 0.8rem;
                border-radius: 6px;
                background: rgba(255, 255, 255, 0.5);
            }

            .top-navbar .user-section .logout-btn:hover {
                color: #fff;
                background: var(--primary-green);
                transform: translateX(2px);
            }

            .top-navbar .user-section .logout-btn i {
                font-size: 0.9rem;
            }

            .brand {
                font-weight: bold;
                color: #fff;
                font-size: 1.5rem;
                padding: 1rem;
                text-transform: uppercase;
                letter-spacing: 1px;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            }

            .dropdown-menu {
                min-width: 180px;
                border-radius: 8px;
                border: none;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                padding: 0.5rem;
            }

            .btn-toggle-nav {
                margin-left: 1rem;
            }

            /* Responsive Design */
            @media (max-width: 768px) {
                .sidebar {
                    transform: translateX(-100%);
                }

                .sidebar.show {
                    transform: translateX(0);
                }

                .main-content {
                    margin-left: 0;
                }
            }

            /* Custom Icons */
            .nav-link[href*="dashboard"] i { color: #e7f4e7; }
            .nav-link[href*="products"] i { color: #d6e2dc; }
            .nav-link[href*="inventory"] i { color: #a5c9b2; }
        </style>
        @stack('styles')
    </head>
    <body>
        <div class="sidebar d-flex flex-column p-3">
            <div class="mb-4 d-flex align-items-center">
                <i class="fas fa-leaf me-2" style="color: #a5c9b2;"></i>
                <span class="brand">Burondwa</span>
            </div>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a class="nav-link dropdown-toggle {{ request()->is('products*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#productsMenu">
                        <i class="fas fa-seedling"></i> Products
                    </a>
                    <div class="collapse {{ request()->is('products*') ? 'show' : '' }}" id="productsMenu">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a href="{{ route('products.index') }}" class="dropdown-item"><i class="fas fa-list-ul"></i> All Products</a></li>
                            <li><a href="{{ route('products.create') }}" class="dropdown-item"><i class="fas fa-plus-circle"></i> Add Product</a></li>
                            <li><a href="{{ route('product.categories.index') }}" class="dropdown-item"><i class="fas fa-tags"></i> Categories</a></li>
                            <li><a href="{{ route('product.sales.index') }}" class="dropdown-item"><i class="fas fa-cash-register"></i> Sales</a></li>
                            <li><a href="{{ route('product.stock.sheet') }}" class="dropdown-item"><i class="fas fa-clipboard-list"></i> Stock Sheet</a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a class="nav-link dropdown-toggle {{ request()->is('inventory*') ? 'active' : '' }}" data-bs-toggle="collapse" href="#inventoryMenu">
                        <i class="fas fa-warehouse"></i> Inventory
                    </a>
                    <div class="collapse {{ request()->is('inventory*') ? 'show' : '' }}" id="inventoryMenu">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                            <li><a href="{{ route('inventory.index') }}" class="dropdown-item"><i class="fas fa-boxes"></i> All Items</a></li>
                            <li><a href="{{ route('inventory.create') }}" class="dropdown-item"><i class="fas fa-plus-circle"></i> Add Item</a></li>
                            <li><a href="{{ route('inventory.categories.index') }}" class="dropdown-item"><i class="fas fa-sitemap"></i> Categories</a></li>
                            <li><a href="{{ route('inventory.suppliers.index') }}" class="dropdown-item"><i class="fas fa-truck"></i> Suppliers</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a href="{{ route('generaldata.index') }}" class="nav-link {{ request()->is('generaldata*') ? 'active' : '' }}">
                        <i class="fas fa-database"></i> General Data
                    </a>
                </li>
            </ul>
        </div>
        <div class="main-content">
            <div class="top-navbar">
                <button class="btn d-md-none" onclick="document.querySelector('.sidebar').classList.toggle('show')">
                    <i class="fas fa-bars"></i>
                </button>
                <div></div> <!-- Empty div to maintain space-between -->
                <div class="user-section">
                    <span class="user-name">
                        <i class="fas fa-user-circle"></i>
                        {{ Auth::user()->name ?? 'User' }}
                    </span>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link logout-btn p-0">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
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
