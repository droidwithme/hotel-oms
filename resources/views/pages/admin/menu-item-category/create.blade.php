@extends('pages.admin.injector.injector')

@section('sub-title')
    Product Category - Create
@endsection

@section('sub-custom-styles')
@endsection

@section('sub-custom-scripts')
@endsection

@section('page-content')
    <div class="main-container container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-12">
                @include('layouts.messages')
                <form class="form-horizontal" method="POST" action="{{ route('admin.menu-item-category.store') }}" enctype="multipart/form-data">
                    <div class="card text-white bg-dark mb-3">
                        <div class="card-header">
                            <h2 class="m-0">Create New Product Category</h2>
                        </div>
                        <div class="card-body">
                            {{ csrf_field() }}
                            <!-- category name -->
                            <div class="form-group mb-0">
                                <label class="col-form-label" for="category-name">Category Name</label>
                                <input type="text" name="category-name" class="form-control" id="category-name" value="{{ old('category-name') }}" required autofocus>
                            </div>
                            <!-- /category name -->
                            <!-- category image -->
                            <div class="form-group mb-0">
                                <label class="col-form-label" for="category-image">Category Image (Optional)</label>
                                <input type="file" name="category-image" class="form-control" id="category-image" />
                            </div>
                            <!-- /category image -->
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col">
                                    <!-- submit -->
                                    <button type="submit" class="btn btn-block btn-success">Create</button>
                                    <!-- /submit -->
                                </div>
                                <div class="col">
                                    <!-- submit -->
                                    <a href="{{ route('admin.menu-item-category.index') }}" class="btn btn-block btn-danger">Cancel</a>
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
