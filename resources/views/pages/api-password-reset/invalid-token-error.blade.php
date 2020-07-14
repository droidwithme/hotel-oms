@extends('layouts.master')

@section('title')
    Konk Store - Password Reset | Invalid Token
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
            window.open('','_parent','');
            window.close();
        }, 10000)
    </script>
@endsection

@section('page-content')
    <div class="main-container container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-12">
                    <div class="card text-white bg-danger mb-3">
                        <div class="card-header">
                            <h2 class="m-0">Invalid Token</h2>
                        </div>
                        <div class="card-body">
                            <p>The given token is invalid or has expired. please request another one.</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
