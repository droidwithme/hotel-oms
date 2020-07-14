<meta charset="utf-8">
<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<!-- Csrf Token -->
<meta name="csrf-token" content="{{ csrf_token() }}" />
<!--  Mobile Viewport Fix -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<!-- Website meta data -->
<meta content="Konk Stores" name="description">
<meta content="Yasin" name="author">
<title>
    @yield('title')
</title>

@include('layouts.styles')