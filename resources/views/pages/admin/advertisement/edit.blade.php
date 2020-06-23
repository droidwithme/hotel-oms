@extends('pages.admin.injector.injector')

@section('sub-title')
    Advertisement - Edit
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
                <form class="form-horizontal" method="POST" action="{{ route('admin.advertisement.update',$item->id) }}" enctype="multipart/form-data">
                    <div class="card text-white bg-dark mb-3">
                        <div class="card-header">
                            <h2 class="m-0">Edit Advertisement</h2>
                        </div>
                        <div class="card-body">
                            {{ csrf_field() }}
                            {{ method_field('patch') }}
                            <!-- advertisement title -->
                            <div class="form-group mb-0">
                                <label class="col-form-label" for="title">Advertisement Title</label>
                                <input type="text" name="title" class="form-control" id="title" value="{{ old('title', $item->title) }}" required autofocus>
                            </div>
                            <!-- /advertisement title -->
                            <!-- advertisement description -->
                            <div class="form-group mb-0">
                                <label class="col-form-label" for="description">Advertisement Description</label>
                                <textarea name="description" class="form-control" id="description" required>{{ old('description', $item->description) }}</textarea>
                            </div>
                            <!-- /advertisement description -->
                            <!-- advertisement image -->
                            <div class="form-group mb-0">
                                <label class="col-form-label" for="image">Advertisement Image</label>
                                <input type="file" name="image" class="form-control" id="image"/>
                            </div>
                            <!-- /advertisement image -->
                            @if(isset($item->image))
                            <!-- previous advertisement image -->
                                <div class="form-group mb-0">
                                    <label class="col-form-label" for="category-image">Previous Advertisement Image</label>
                                    <div class="rounded bg-white p-2">
                                        <img src="{{ asset('assets/images/advertisement-images/'.$item->image) }}" class="rounded" style="display: block; max-width: 100%; max-height: 250px; margin: 0 auto;">
                                    </div>
                                </div>
                                <!-- /previous advertisement image -->
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
                                    <a href="{{ route('admin.advertisement.index') }}" class="btn btn-block btn-danger">Cancel</a>
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
