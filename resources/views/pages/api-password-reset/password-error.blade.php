@extends('layouts.master')

@section('title')
    Konk Store - Password Reset | Password Error
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
        setTimeout(function closeWindow() {
            window.history.back();
        }, 10000)
    </script>
@endsection

@section('page-content')
    <div class="main-container container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-12">
                    <div class="card text-white bg-danger mb-3">
                        <div class="card-header">
                            <h2 class="m-0">Password Errors</h2>
                        </div>
                        <div class="card-body">
                            @if($error == 'required')<p>The password and confirm password are required</p>@endif
                            @if($error == 'length')<p>The password and confirm password must be of 6 characters</p>@endif
                            @if($error == 'confirm')<p>The confirm password must match the password</p>@endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
