@extends('layouts.master')

@section('title')
    Konk - Store Change Password
@endsection

@section('custom-styles')
    <style type="text/css">
        .main-container{
            margin-top: 60px;
        }
    </style>
@endsection

@section('custom-scripts')
    <script>
        $(document).ready(function() {
            $('.alert').delay(1000).fadeOut(2000);
        });
    </script>
@endsection

@section('page-content')
    <div class="main-container container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-12">
                @include('layouts.messages')
                <form class="form-horizontal" method="POST" action="{{ route('hotel.auth.forgot-password.reset-password.update', $token) }}">
                    <div class="card text-white bg-dark mb-3">
                        <div class="card-header">
                            <h2 class="m-0">Change Your Password</h2>
                        </div>
                        <div class="card-body">
                            {{ csrf_field() }}
                            <!-- password -->
                            <div class="form-group">
                                <label class="col-form-label" for="password">Password</label>
                                <input type="password" name="password" class="form-control" id="password" minlength="6" required>
                            </div>
                            <!-- /password -->
                            <!-- confirm password -->
                            <div class="form-group mb-0">
                                <label class="col-form-label" for="confirm-password">Confirm Password</label>
                                <input type="password" name="confirm-password" class="form-control" id="confirm-password" minlength="6" required>
                            </div>
                            <!-- /confirm password -->
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col">
                                    <!-- submit -->
                                    <button type="submit" class="btn btn-block btn-success">Change Password</button>
                                    <!-- /submit -->
                                </div>
                                <div class="col">
                                    <!-- submit -->
                                    <a href="{{ route('login') }}" class="btn btn-block btn-danger">Cancel</a>
                                    <!-- /submit -->
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
