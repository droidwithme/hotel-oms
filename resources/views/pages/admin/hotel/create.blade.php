@extends('pages.admin.injector.injector')

@section('sub-title')
    Store - Create
@endsection

@section('sub-custom-styles')
@endsection

@section('sub-custom-scripts')
    <script>
        $(document).ready(function(){
            $("#hotel_category").select2({ placeholder: 'Select A Category' });
            $("#mobile,#lat,#long").keydown(function (e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190, 110, 109, 173]) !== -1 ||
                    // Allow: Ctrl+A, Command+A
                    ((e.keyCode == 65 || e.keyCode == 86 || e.keyCode == 67) && (e.ctrlKey === true || e.metaKey === true)) ||
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
            <div class="col-lg-6 col-md-8 col-sm-12">
                @include('layouts.messages')
                <form class="form-horizontal" method="POST" action="{{ route('admin.hotel.store') }}" enctype="multipart/form-data">
                    <div class="card text-white bg-dark mb-3">
                        <div class="card-header">
                            <h2 class="m-0">Create New Store</h2>
                        </div>
                        <div class="card-body">
                            {{ csrf_field() }}
                            <!-- name -->
                            <div class="form-group">
                                <label class="col-form-label" for="name">Store Name</label>
                                <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" required autofocus>
                            </div>
                            <!-- /name -->
                            <!-- email -->
                            <div class="form-group">
                                <label class="col-form-label" for="email">Email (Optional)</label>
                                <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" autofocus>
                            </div>
                            <!-- /email -->
                            <!-- mobile -->
                            <div class="form-group">
                                <label class="col-form-label" for="mobile">Mobile</label>
                                <input type="text" name="mobile" class="form-control" id="mobile" value="{{ old('mobile') }}" minlength="10" maxlength="13" required autofocus>
                            </div>
                            <!-- /mobile -->
                            <!-- address -->
                            <div class="form-group">
                                <label class="col-form-label" for="address">Address</label>
                                <textarea name="address" class="form-control" id="address" required>{{ old('address') }}</textarea>
                            </div>
                            <!-- /address -->
                            <!-- category -->
                            <div class="form-group">
                                <label class="col-form-label" for="hotel_category">Store Category Name</label>
                                <select name="hotel_category" id="hotel_category" class="form-control" required>
                                    <option value="">Select Store Category</option>
                                    @if(isset($hotelCategoryList))
                                        @foreach($hotelCategoryList as $item)
                                            <option value="{{ $item->id }}" {{ ($item->id == old('hotel_category'))? 'selected': '' }}>{{ $item->category_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <!-- /category -->
                            <!-- lat -->
                            <div class="form-group">
                                <label class="col-form-label" for="lat">Latitude</label>
                                <input type="text" name="lat" class="form-control" id="lat" value="{{ old('lat') }}" required>
                            </div>
                            <!-- /lat -->
                            <!-- long -->
                            <div class="form-group">
                                <label class="col-form-label" for="long">Longitude</label>
                                <input type="text" name="long" class="form-control" id="long" value="{{ old('long') }}" required>
                            </div>
                            <!-- /long -->
                            <!-- password -->
                            <div class="form-group">
                                <label class="col-form-label" for="password">Password</label>
                                <input type="password" name="password" class="form-control" id="password" minlength="6" required>
                            </div>
                            <!-- /password -->
                            <!-- confirm password -->
                            <div class="form-group">
                                <label class="col-form-label" for="confirm-password">Confirm Password</label>
                                <input type="password" name="confirm-password" class="form-control" id="confirm-password" minlength="6" required>
                            </div>
                            <!-- /confirm password -->
                            <!-- store image -->
                            <div class="form-group mb-0">
                                <label class="col-form-label" for="hotel-image">Store Image (Optional)</label>
                                <input type="file" name="hotel-image" class="form-control" id="hotel-image" />
                            </div>
                            <!-- /store image -->
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
                                    <a href="{{ route('admin.hotel.index') }}" class="btn btn-block btn-danger">Cancel</a>
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
