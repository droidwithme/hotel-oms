<!-- navigation -->
<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
    <a href="{{ route('hotel.order.proceeded') }}" class="navbar-brand">Store - {{ Auth::user()->name }}</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <!-- Order -->
            <li class="nav-item dropdown {{ Request::is('store/order*')? 'active' : '' }}">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Orders
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('hotel.order.proceeded') }}">Proceeded Orders</a>
                    <a class="dropdown-item" href="{{ route('hotel.order.completed') }}">Completed Orders</a>
                    <a class="dropdown-item" href="{{ route('hotel.order.all') }}">Show All</a>
                </div>
            </li>
            <!-- /Order -->
            <!-- profile -->
            <li class="nav-item dropdown {{ (Request::is('store/profile/*'))? 'active' : '' }}">
                <a class="nav-link" href="{{ route('hotel.profile.edit') }}">Edit Profile</a>
            </li>
            <!-- /profile -->
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('hotel.auth.logout') }}"><i class="fa fa-sign-out-alt "></i> Logout</a>
            </li>
        </ul>
    </div>
</nav>
<!-- /navigation -->