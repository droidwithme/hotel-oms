@extends('pages.admin.injector.injector')

@section('sub-title')
    Store Product - Deleted
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

            // for showing restore item popup
            $(document).on('click',"#restore-item",function(){
                $(this).addClass('restore-item-trigger-clicked');

                var options = {'backdrop':'static'};
                $('#restore-modal').modal(options)
                $("#restore-modal-label").text('Restore entry?')
            })

            // on click of confirmation
            $(document).on('click',"#restore-action-confirm",function(){
                $('.restore-item-trigger-clicked').siblings("#restore-data-form").submit();
            })

            //  on modal hide
            $('#restore-modal').on('hide.bs.modal',function(){
                $('.restore-item-trigger-clicked').removeClass('restore-item-trigger-clicked')
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
                    <h1 class="text-primary mr-auto">{{ $hotel->name }} - Deleted Products</h1>
                </div>
                <div class="col align-self-center">
                    <a href="{{ route('admin.hotel.menu-item.index', $hotel->id) }}" class="btn btn-outline-primary float-right m-1">Not Deleted Items</a>
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
                                <form method="POST" id="restore-data-form" action="{{ route('admin.hotel.menu-item.deleted.restore', ['hotelId'=>$hotel->id, 'id'=>$item->id]) }}" hidden>
                                    {{ csrf_field() }}
                                    {{ method_field('PUT') }}
                                </form>
                                <button type="button" class="btn btn-primary" id="restore-item"><i class="fa fa-sync"></i> Restore</button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <!-- /table -->
    </div>
    <!-- Restore Modal -->
    <div class="modal fade" id="restore-modal" tabindex="-1" role="dialog" aria-labelledby="restore-modal-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="restore-modal-label">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-body-content">
                    <h4 class="text-center">Are you sure you want to restore this entry?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" id="restore-action-confirm" class="btn btn-primary">Yes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Restore Modal -->
@endsection
