@extends('pages.admin.injector.injector')

@section('sub-title')
    Store Product - Duplicate
@endsection

@section('sub-custom-styles')
@endsection

@section('sub-custom-scripts')
    <script>
        $(document).ready(function(){
            $("#item-category").select2({ placeholder: 'Select A Category' });
            $("#item-price").keydown(function (e) {
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
                <form class="form-horizontal" method="POST" action="{{ route('admin.hotel.menu-item.duplicate.store', $hotelId) }}" enctype="multipart/form-data">
                    <div class="card text-white bg-dark mb-3">
                        <div class="card-header">
                            <h2 class="m-0">Duplicate Item ({{ $menuItem->item_title }})</h2>
                        </div>
                        <div class="card-body">
                            {{ csrf_field() }}
                            <!-- category -->
                            <div class="form-group">
                                <label class="col-form-label" for="item-category">Item Category</label>
                                <select name="item-category" id="item-category" class="form-control" required>
                                    <option value="">Select Item Category</option>
                                    @if(isset($itemCategoryList))
                                        @foreach($itemCategoryList as $itemCategory)
                                            <option value="{{ $itemCategory->id }}" {{ ($itemCategory->id == old('item-category', $menuItem->item_category))? 'selected': '' }}>{{ $itemCategory->category_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <!-- /category -->
                            <!-- item name -->
                            <div class="form-group">
                                <label class="col-form-label" for="item-name">Item Name</label>
                                <input type="text" name="item-name" class="form-control" id="item-name" value="{{ old('item-name', $menuItem->item_title) }}" required autofocus>
                            </div>
                            <!-- /item name -->
                            <!-- item ingredients -->
                            <div class="form-group">
                                <label class="col-form-label" for="item-ingredients">Item Ingredients (Optional)</label>
                                <textarea name="item-ingredients" class="form-control" id="item-ingredients">{{ old('item-ingredients', $menuItem->item_ingredients) }}</textarea>
                            </div>
                            <!-- /item ingredients -->
                            <!-- item price -->
                            <div class="form-group">
                                <label class="col-form-label" for="item-price">Item Price (in Rupees)</label>
                                <input type="text" name="item-price" class="form-control" id="item-price" value="{{ old('item-price', $menuItem->item_price) }}" required autofocus>
                            </div>
                            <!-- /item price -->
                            <!-- item photo -->
                            <div class="form-group mb-0">
                                <label class="col-form-label" for="item-photos">Item Images (Multiple) (Optional)</label>
                                <input type="file" name="item-photos[]" class="form-control" id="item-photos" multiple />
                            </div>
                            <!-- /item photo -->
                            <!-- duplicated photos -->
                            @if($menuItem->item_photo->count() > 0)
                                @foreach($menuItem->item_photo as $photo)
                                    <input type="text" name="duplicated-photos[]" value="{{ $photo->item_photo }}" hidden>
                                @endforeach
                            @endif
                            <!-- /duplicated photos -->
                            @if($menuItem->item_photo->count() > 0)
                            <!-- previous product category image -->
                                <div class="form-group mb-0">
                                    <label class="col-form-label" for="category-image">Previous Item Image</label>
                                    <div class="rounded bg-white p-2">
                                        @foreach($menuItem->item_photo as $photo)
                                            <img src="{{ asset('assets/images/menu-item-images/'.$photo->item_photo) }}" class="rounded" style="display: block; max-width: 100%; max-height: 250px; margin: 0 auto;{{ ($loop->iteration != 1)? 'margin-top: 10px;': '' }}">
                                        @endforeach
                                    </div>
                                </div>
                                <!-- /previous product category image -->
                            @endif
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col">
                                    <!-- submit -->
                                    <button type="submit" class="btn btn-block btn-success">Duplicate</button>
                                    <!-- /submit -->
                                </div>
                                <div class="col">
                                    <!-- submit -->
                                    <a href="{{ route('admin.hotel.menu-item.index', $hotelId) }}" class="btn btn-block btn-danger">Cancel</a>
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
