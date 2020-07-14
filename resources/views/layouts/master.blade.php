<!DOCTYPE html>
<html>
    <head>
        @include('layouts.head')
    </head>
    <body>
        @if(Auth::check())
            @if(Auth::user()->role == 'admin')
                @include('layouts.navigation.admin')
            @else
                @include('layouts.navigation.hotel')
            @endif
        @else
            @include('layouts.navigation.auth')
        @endif

        @yield('page-content')


        @include('layouts.scripts')
    </body>
</html>