<!-- navigation -->
<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
    <a href="{{ route('admin.dashboard.index') }}" class="navbar-brand">Admin - {{ Auth::user()->name }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <!-- Dashboard -->
            <li class="nav-item {{ Request::is('admin/dashboard*')? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.dashboard.index') }}">Dashboard</a>
            </li>
            <!-- /Dashboard -->
            <!-- Order -->
            <li class="nav-item dropdown {{ Request::is('admin/order*')? 'active' : '' }}">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Orders
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('admin.order.new') }}">New Orders</a>
                    <a class="dropdown-item" href="{{ route('admin.order.proceeded') }}">Proceeded Orders</a>
                    <a class="dropdown-item" href="{{ route('admin.order.completed') }}">Completed Orders</a>
                    <a class="dropdown-item" href="{{ route('admin.order.all') }}">Show All</a>
                </div>
            </li>
            <!-- /Order -->
            <!-- Store -->
            <li class="nav-item dropdown {{ (Request::is('admin/store*') && !Request::is('admin/store-category*'))? 'active' : '' }}">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Store
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('admin.hotel.index') }}">Show All</a>
                    <a class="dropdown-item" href="{{ route('admin.hotel.create') }}">Create New</a>
                    <a class="dropdown-item" href="{{ route('admin.hotel.deleted.show') }}">Deleted Stores</a>
                </div>
            </li>
            <!-- /Store -->
            <!-- Store Category -->
            <li class="nav-item dropdown {{ Request::is('admin/store-category*')? 'active' : '' }}">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Store Categories
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('admin.hotel-category.index') }}">Show All</a>
                    <a class="dropdown-item" href="{{ route('admin.hotel-category.create') }}">Create New</a>
                    <a class="dropdown-item" href="{{ route('admin.hotel-category.deleted.show') }}">Deleted Category</a>
                </div>
            </li>
            <!-- /Store Category -->
            <!-- Product Category -->
            <li class="nav-item dropdown {{ Request::is('admin/product-category*')? 'active' : '' }}">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Product Categories
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('admin.menu-item-category.index') }}">Show All</a>
                    <a class="dropdown-item" href="{{ route('admin.menu-item-category.create') }}">Create New</a>
                    <a class="dropdown-item" href="{{ route('admin.menu-item-category.deleted.show') }}">Deleted Category</a>
                </div>
            </li>
            <!-- /Product Category -->
            <!-- Advertisement -->
            <li class="nav-item dropdown {{ Request::is('admin/advertisement*')? 'active' : '' }}">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Advertisement
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('admin.advertisement.index') }}">Show All</a>
                    <a class="dropdown-item" href="{{ route('admin.advertisement.create') }}">Create New</a>
                    <a class="dropdown-item" href="{{ route('admin.advertisement.deleted.show') }}">Deleted Advertisement</a>
                </div>
            </li>
            <!-- /Advertisement -->
            <!-- App User -->
            <li class="nav-item dropdown {{ (Request::is('admin/app-user*'))? 'active' : ''  }}">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    App Users
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('admin.app-user.index') }}">Show All</a>
                </div>
            </li>
            <!-- /App User -->
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('admin.auth.logout') }}"><i class="fa fa-sign-out-alt "></i> Logout</a>
            </li>
        </ul>
    </div>
</nav>
<!-- /navigation -->