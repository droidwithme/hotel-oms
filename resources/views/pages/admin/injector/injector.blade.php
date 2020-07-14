@extends('layouts.master')

@section('title')
    Konk - Admin | @yield('sub-title')
@endsection

@section('custom-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.min.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2.css')}}">
    <style type="text/css">
        .main-container{
            margin-top: 80px;
            margin-bottom: 80px;
        }

        .word-break{
            word-break: break-all;
            word-break: break-word;
        }
    </style>
    @yield('sub-custom-styles')
@endsection

@section('custom-scripts')
    <script type="text/javascript" src="{{ asset("assets/js/datatables.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("assets/js/select2.js") }}"></script>
    <script>
        $(document).ready(function(){
            $('.alert').delay(1000).fadeOut(2000);
            $('#myTable').DataTable();
        })
    </script>
    @yield('sub-custom-scripts')
@endsection
