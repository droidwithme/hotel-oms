@extends('layouts.master')

@section('title')
    Konk - Admin Login
@endsection

@section('custom-styles')
    <style type="text/css">
        .main-container{
            margin-top: 60px;
        }
    </style>
@endsection

@section('custom-scripts')
@endsection

@section('page-content')
<div class="main-container container">
    <div class="row justify-content-center">
        <div class="col-sm-6">
            <form class="form-horizontal" method="POST" action="{{ route('admin.auth.doLogin') }}">
                <div class="card text-white bg-dark mb-3">
                    <div class="card-header text-center">
                        <h2>Admin Login</h2>
                    </div>
                    <div class="card-body">
                        {{ csrf_field() }}
                        <!-- name -->
                        <div class="form-group">
                            <label class="col-form-label" for="email">E-mail</label>
                            <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" required autofocus>
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
                        <!-- error message -->
                        @if ($errors->has('email') || $errors->has('password'))
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
