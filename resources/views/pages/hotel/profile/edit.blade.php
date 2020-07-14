@extends('pages.admin.injector.injector')

@section('sub-title')
    Store - Edit Profile
@endsection

@section('sub-custom-styles')
@endsection

@section('sub-custom-scripts')
    <script>
        $(document).ready(function(){
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
                <form class="form-horizontal" method="POST" action="{{ route('hotel.profile.update') }}" enctype="multipart/form-data">
                    <div class="card text-white bg-dark mb-3">
                        <div class="card-header">
                            <h2 class="m-0">Edit Your Profile</h2>
                        </div>
                        <div class="card-body">
                            {{ csrf_field() }}
                            {{ method_field('patch') }}
                            <!-- name -->
                            <div class="form-group">
                                <label class="col-form-label" for="name">Store Name</label>
                                <input type="text" name="name" class="form-control" id="name" value="{{ old('name', $item->name) }}" required autofocus>
                            </div>
                            <!-- /name -->
                            <!-- email -->
                            <div class="form-group">
                                <label class="col-form-label" for="email">Email</label>
                                <input type="email" name="email" class="form-control" id="email" value="{{ old('email', $item->email) }}" autofocus>
                            </div>
                            <!-- /email -->
                            <!-- mobile -->
                            <div class="form-group">
                                <label class="col-form-label" for="mobile">Mobile</label>
                                <input type="text" name="mobile" class="form-control" id="mobile" value="{{ old('mobile', $item->mobile) }}" minlength="10" maxlength="13" autofocus>
                            </div>
                            <!-- /mobile -->
                            <!-- address -->
                            <div class="form-group">
                                <label class="col-form-label" for="address">Address</label>
                                <textarea name="address" class="form-control" id="address" required>{{ old('address', $item->address) }}</textarea>
                            </div>
                            <!-- /address -->
                            <!-- lat -->
                            <div class="form-group">
                                <label class="col-form-label" for="lat">Latitude</label>
                                <input type="text" name="lat" class="form-control" id="lat" value="{{ old('lat', $item->lat) }}" required>
                            </div>
                            <!-- /lat -->
                            <!-- long -->
                            <div class="form-group">
                                <label class="col-form-label" for="long">Longitude</label>
                                <input type="text" name="long" class="form-control" id="long" value="{{ old('long', $item->long) }}" required>
                            </div>
                            <!-- /long -->
                            <!-- password -->
                            <div class="form-group">
                                <label class="col-form-label" for="password">Password (Leave blank for no change)</label>
                                <input type="password" name="password" class="form-control" id="password" minlength="6">
                            </div>
                            <!-- /password -->
                            <!-- confirm password -->
                            <div class="form-group mb-0">
                                <label class="col-form-label" for="confirm-password">Confirm Password</label>
                                <input type="password" name="confirm-password" class="form-control" id="confirm-password" minlength="6">
                            </div>
                            <!-- /confirm password -->
                                <!-- store image -->
                                <div class="form-group">
                                    <label class="col-form-label" for="hotel-image">Profile Image (Optional)</label>
                                    <input type="file" name="hotel-image" class="form-control" id="product-image" />
                                </div>
                                <!-- /store image -->
                            @if(isset($item->hotel_image))
                                <!-- previous store image -->
                                    <div class="form-group mb-0">
                                        <label class="col-form-label" for="category-image">Previous Profile Image</label>
                                        <div class="rounded bg-white p-2">
                                            <img src="{{ asset('assets/images/hotel-images/'.$item->hotel_image) }}" class="rounded" style="display: block; max-width: 100%; max-height: 250px; margin: 0 auto;">
                                        </div>
                                    </div>
                                    <!-- /previous store image -->
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
                                    <a href="{{ route('hotel.order.proceeded') }}" class="btn btn-block btn-danger">Cancel</a>
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
