@extends('pages.admin.injector.injector')

@section('sub-title')
    Store Category - Edit
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
                <form class="form-horizontal" method="POST" action="{{ route('admin.hotel-category.update',$item->id) }}" enctype="multipart/form-data">
                    <div class="card text-white bg-dark mb-3">
                        <div class="card-header">
                            <h2 class="m-0">Edit Store Category</h2>
                        </div>
                        <div class="card-body">
                            {{ csrf_field() }}
                            {{ method_field('patch') }}
                            <!-- category name -->
                            <div class="form-group mb-0">
                                <label class="col-form-label" for="category-name">Category Name</label>
                                <input type="text" name="category-name" class="form-control" id="category-name" value="{{ old('category-name', $item->category_name) }}" required autofocus>
                            </div>
                            <!-- /category name -->
                            <!-- category image -->
                            <div class="form-group">
                                <label class="col-form-label" for="category-image">Category Image</label>
                                <input type="file" name="category-image" class="form-control" id="category-image" />
                            </div>
                            <!-- /category image -->
                            @if(isset($item->category_image))
                                <!-- previous category image -->
                                <div class="form-group mb-0">
                                    <label class="col-form-label" for="category-image">Previous Category Image</label>
                                    <div class="rounded bg-white p-2">
                                        <img src="{{ asset('assets/images/hotel-category-images/'.$item->category_image) }}" class="rounded" style="display: block; max-width: 100%; max-height: 250px; margin: 0 auto;">
                                    </div>
                                </div>
                                <!-- /previous category image -->
                            @endif
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col">
                                    <!-- submit -->
                                    <button type="submit" class="btn btn-block btn-success">Update</button>
                                    <!-- /submit -->
                                </div>
                                <div class="col">
                                    <!-- submit -->
                                    <a href="{{ route('admin.hotel-category.index') }}" class="btn btn-block btn-danger">Cancel</a>
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