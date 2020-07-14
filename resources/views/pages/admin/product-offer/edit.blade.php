@extends('pages.admin.injector.injector')

@section('sub-title')
    Product Offer - Edit
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
                <form class="form-horizontal" method="POST" action="{{ route('admin.hotel.menu-item.offer.update',['hotelId'=>$hotelId, 'productId'=>$productId , 'id'=>$item->id]) }}">
                    <div class="card text-white bg-dark mb-3">
                        <div class="card-header">
                            <h2 class="m-0">Edit Product Offer</h2>
                        </div>
                        <div class="card-body">
                            {{ csrf_field() }}
                            {{ method_field('patch') }}
                            <!-- offer name -->
                            <div class="form-group">
                                <label class="col-form-label" for="offer-name">Offer Name</label>
                                <input type="text" name="offer-name" class="form-control" id="offer-name" value="{{ old('offer-name', $item->offer_name) }}" required autofocus>
                            </div>
                            <!-- /offer name -->
                            <!-- offer description -->
                            <div class="form-group">
                                <label class="col-form-label" for="offer-description">Offer Description</label>
                                <textarea type="text" name="offer-description" class="form-control" id="offer-description" required>{{ old('offer-description', $item->offer_description) }}</textarea>
                            </div>
                            <!-- /offer description -->
                            <!-- offer type -->
                            <div class="form-group">
                                <label class="col-form-label" for="offer-type">Offer Type</label>
                                <select name="offer-type" id="offer-type" class="form-control">
                                    <option value="0" {{ (old('offer-type', $item->offer_type) == '0')? 'selected': '' }}>Discount</option>
                                    <option value="1" {{ (old('offer-type', $item->offer_type) == '1')? 'selected': '' }}>Amount Off</option>
                                    <option value="2" {{ (old('offer-type', $item->offer_type) == '2')? 'selected': '' }}>Buy 1 Get Product(s) Free.</option>
                                </select>
                            </div>
                            <!-- /offer type -->
                            <!-- offer type detail -->
                            <div class="form-group mb-0">
                                <label class="col-form-label" for="offer-type-detail">Discount amount in %/ Amount off/ Number of products free</label>
                                <input type="text" name="offer-type-detail" class="form-control" id="offer-type-detail" value="{{ old('offer-type-detail', ($item->discount_amount != null && $item->discount_amount != '')?$item->discount_amount: $item->products_free_quantity ) }}" required>
                            </div>
                            <!-- /offer type detail -->
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
                                    <a href="{{ route('admin.hotel.menu-item.offer.index', ['hotelId'=>$hotelId, 'productId'=>$productId]) }}" class="btn btn-block btn-danger">Cancel</a>
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
