@extends('pages.admin.injector.injector')

@section('sub-title')
    Dashboard
@endsection

@section('sub-custom-styles')
@endsection

@section('sub-custom-scripts')
@endsection

@section('page-content')
    <div class="main-container container-fluid">
    @include('layouts.messages')
    <!-- counter cards -->
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h1 class="text-primary mr-auto">Statistics</h1>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 col-md-4 mb-4">
                    <!-- card -->
                    <div class="bg-primary rounded p-3">
                        <h5 class="text-white" style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis;min-height: 30px;">Stores</h5>
                        <div class="row">
                            <div class="col">
                                <a href="{{ route('admin.hotel.index') }}" style="text-decoration: none !important;">
                                    <div class="bg-white rounded p-4">
                                        <h5 class="text-primary m-0">{{ $hotelCount }}</h5>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- /card -->
                </div>
                <div class="col-12 col-md-4 mb-4">
                    <!-- card -->
                    <div class="bg-primary rounded p-3">
                        <h5 class="text-white" style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis;min-height: 30px;min-height: 30px;">Stores Categories</h5>
                        <div class="row">
                            <div class="col">
                                <a href="{{ route('admin.hotel-category.index') }}"
                                style="text-decoration: none !important;">
                                    <div class="bg-white rounded p-4">
                                        <h5 class="text-primary m-0">{{ $hotelCategoryCount }}</h5>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- /card -->
                </div>
                <div class="col-12 col-md-4 mb-4">
                    <!-- card -->
                    <div class="bg-primary rounded p-3">
                        <h5 class="text-white" style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis;min-height: 30px;min-height: 30px;">Orders</h5>
                        <div class="row">
                            <div class="col">
                                <a href="{{ route('admin.order.all') }}"
                                   style="text-decoration: none !important;">
                                    <div class="bg-white rounded p-4">
                                        <h5 class="text-primary m-0">{{ $orderCount }}</h5>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- /card -->
                </div>
                <div class="col-12 col-md-12 mb-4">
                    <!-- card -->
                    <div class="bg-primary rounded p-3">
                        <h5 class="text-white" style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis;min-height: 30px;">App Users</h5>
                        <div class="row">
                            <div class="col">
                                <a href="{{ route('admin.app-user.index') }}"
                                style="text-decoration: none !important;">
                                    <div class="bg-white rounded p-4">
                                        <h5 class="text-primary m-0">{{ $appUsers }}</h5>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- /card -->
                </div>
            </div>
        </div>
        <!-- /counter cards -->
    </div>
@endsection
