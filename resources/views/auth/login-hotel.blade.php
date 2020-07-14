@extends('layouts.master')

@section('title')
    Konk - Store Login
@endsection

@section('custom-styles')
    <style type="text/css">
        .main-container{
            margin-top: 60px;
        }

        .forgot-password-link{
            color: #FFFFFF !important;
            text-align: center;
            margin-top: 20px;
            display: block;
            width: 100%;
        }
    </style>
@endsection

@section('custom-scripts')
    <script>
        $(document).ready(function(){
            $('.alert').delay(1000).fadeOut(2000);

            $("#mobile").keydown(function (e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
                    // Allow: Ctrl+A, Command+A
                    (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                    // Allow: home, end, left, right, down, up
                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        })
    </script>
@endsection

@section('page-content')
<div class="main-container container">
    <div class="row justify-content-center">
        <div class="col-sm-6">
            @if (!$errors->has('mobile') && !$errors->has('password'))
            @include('layouts.messages')
            @endif
            <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                <div class="card text-white bg-dark mb-3">
                    <div class="card-header text-center">
                        <h2>Store Login</h2>
                    </div>
                    <div class="card-body">
                        {{ csrf_field() }}
                        <!-- name -->
                        <div class="form-group">
                            <label class="col-form-label" for="mobile">Mobile</label>
                            <input type="text" name="mobile" class="form-control" id="mobile" value="{{ old('mobile') }}" minlength="10" maxlength="13" required autofocus>
                        </div>
                        <!-- /name -->
                        <!-- password -->
                        <div class="form-group">
                            <label for="password" class="col-form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="password" required>
                        </div>
                        <!-- /password -->
                        <!-- submit -->
                        <button type="submit" class="btn btn-block btn-primary" style="background-color: #009688">Login</button>
                        <!-- /submit -->
                        <!-- password reset -->
                        <a href="{{ route('hotel.auth.forgot-password') }}" class="forgot-password-link">Forgot Password</a>
                        <!-- /password reset -->
                        <!-- error message -->
                        @if ($errors->has('mobile') || $errors->has('password'))
                            <div class="p-1 mt-3 bg-danger text-white text-center rounded">The provided credentials don't match any of our records.</div>
                        @endif
                        <!-- /error message -->
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
