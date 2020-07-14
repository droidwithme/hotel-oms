@extends('pages.admin.injector.injector')

@section('sub-title')
    Store Product
@endsection

@section('sub-custom-styles')
    <style type="text/css">
        .item-photo-container{
            display: flex;
            flex-direction: row;
            flex-grow: 1;
            justify-content: center;
        }

        .item-photo-container img{
            display: flex;
        }
        .item-photo-container .extra-photo-count{
            display: flex;
            width: 100px;
            height: 100px;
            margin-left: 10px;
            background-color: #818181;
            border-radius: 3px;
            justify-content: center;
        }

        .extra-photo-count span{

            align-self: center;
            display: flex;
            color: #ffffff;
            font-size: 30px;
            font-weight: bold;
        }
    </style>
@endsection

@section('sub-custom-scripts')
    <script>
        $(document).ready(function(){

            /**
             * for showing delete item popup
             */

            $(document).on('click',"#delete-item",function(){
                $(this).addClass('delete-item-trigger-clicked');

                var options = {'backdrop':'static'};
                $('#delete-modal').modal(options)
                $("#delete-modal-label").text('Delete entry?')
            })

            // on click of confirmation
            $(document).on('click',"#delete-action-confirm",function(){
                $('.delete-item-trigger-clicked').siblings("#delete-data-form").submit();
            })

            //  on modal hide
            $('#delete-modal').on('hide.bs.modal',function(){
                $('.delete-item-trigger-clicked').removeClass('delete-item-trigger-clicked')
            })
         })
    </script>
@endsection

@section('page-content')
    <div class="main-container container-fluid">
        @include('layouts.messages')
        <!-- heading -->
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h1 class="text-primary mr-auto">{{ $hotel->name }} - Products</h1>
                </div>
                <div class="col align-self-center">
                    <a href="{{ route('admin.hotel.menu-item.create', $hotel->id) }}" class="btn btn-outline-primary float-right m-1">Create New</a>
                    <a href="{{ route('admin.hotel.menu-item.deleted.show', $hotel->id) }}" class="btn btn-outline-primary float-right m-1">Deleted Items</a>
                </div>
            </div>
        </div>
        <!-- /heading -->
        <!-- table -->
        <table class="table table-striped table-bordered" id="myTable" cellspacing="0" width="100%">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Product Description</th>
                    <th>Product Price</th>
                    <th>Product Photo</th>
                    <th>Product Category</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if($menuItemList->isNotEmpty())
                    @foreach($menuItemList as $item)
                        <tr>
                            <td class="align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle">{{ $item->item_title }}</td>
                            <td class="align-middle word-break">{{ $item->item_ingredients }}</td>
                            <td class="align-middle">{{ $item->item_price }}</td>
                            <td class="align-middle">
                                @if(isset($item->item_photo))
                                    <div class="item-photo-container">
                                        <img src="{{ asset('assets/images/menu-item-images/'.$item->item_photo) }}" style="width: 100px; height: 100px;">
                                        @if($item->item_extra_photo_count > 0)
                                        <div class="extra-photo-count">
                                            <span>+{{ $item->item_extra_photo_count }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    @else
                                    &mdash;
                                @endif
                            </td>
                            <td class="align-middle">{{ $item->menu_category_name }}</td>
                            <td class="align-middle">
                                <a href="{{ route('admin.hotel.menu-item.duplicate', ['hotelId'=>$hotel->id, 'id'=>$item->id]) }}" class="btn btn-primary m-2"><i class="fa fa-plus"></i> Duplicate</a>
                                <a href="{{ route('admin.hotel.menu-item.offer.index', ['hotelId'=>$hotel->id, 'productId'=>$item->id]) }}" class="btn btn-primary m-2"><i class="fas fa-gift"></i> Offers</a>
                                <a href="{{ route('admin.hotel.menu-item.edit', ['hotelId'=>$hotel->id, 'id'=>$item->id]) }}" class="btn btn-primary m-2"><i class="fa fa-edit"></i> Edit</a>
                                <form method="POST" id="delete-data-form" action="{{ route('admin.hotel.menu-item.delete', ['hotelId'=>$hotel->id, 'id'=>$item->id]) }}" hidden>
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                </form>
                                <button type="button" class="btn btn-danger m-2" id="delete-item"><i class="fa fa-trash"></i> Delete</button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <!-- /table -->
    </div>
    <!-- Delete Modal -->
    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="delete-modal-label">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-body-content">
                    <h4 class="text-center">Are you sure you want to delete this entry?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" id="delete-action-confirm" class="btn btn-primary">Yes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Delete Modal -->
@endsection
