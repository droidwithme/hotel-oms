@extends('pages.admin.injector.injector')

@section('sub-title')
    Advertisement - Create
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
                <form class="form-horizontal" method="POST" action="{{ route('admin.advertisement.store') }}" enctype="multipart/form-data">
                    <div class="card text-white bg-dark mb-3">
                        <div class="card-header">
                            <h2 class="m-0">Create New Advertisement</h2>
                        </div>
                        <div class="card-body">
                            {{ csrf_field() }}
                            <!-- advertisement title -->
                            <div class="form-group mb-0">
                                <label class="col-form-label" for="title">Advertisement Title</label>
                                <input type="text" name="title" class="form-control" id="title" value="{{ old('title') }}" required autofocus>
                            </div>
                            <!-- /advertisement title -->
                            <!-- advertisement description -->
                            <div class="form-group mb-0">
                                <label class="col-form-label" for="description">Advertisement Description</label>
                                <textarea name="description" class="form-control" id="description" required>{{ old('description') }}</textarea>
                            </div>
                            <!-- /advertisement description -->
                            <!-- advertisement image -->
                            <div class="form-group mb-0">
                                <label class="col-form-label" for="image">Advertisement Image</label>
                                <input type="file" name="image" class="form-control" id="image" required />
                            </div>
                            <!-- /advertisement image -->
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
