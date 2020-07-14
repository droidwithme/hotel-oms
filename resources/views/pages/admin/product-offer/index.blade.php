@extends('pages.admin.injector.injector')

@section('sub-title')
    Product Offer
@endsection

@section('sub-custom-styles')
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
    <div class="main-container container">
        @include('layouts.messages')
        <!-- heading -->
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h1 class="text-primary mr-auto">Offers</h1>
                    <h5 class="text-black-50 mr-auto">Product : "{{ $menuItem->item_title }}"</h5>
                </div>
                @if($menuItemOfferList->isEmpty())
                <div class="col align-self-center">
                    <a href="{{ route('admin.hotel.menu-item.offer.create', ['hotelId'=>$hotelId, 'productId'=>$menuItem->id]) }}" class="btn btn-outline-primary float-right">Create New</a>
                </div>
                @endif
            </div>
        </div>
        <!-- /heading -->
        <!-- table -->
        <table class="table table-striped table-bordered" id="myTable" cellspacing="0" width="100%">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Offer Name</th>
                    <th>Offer Description</th>
                    <th>Offer Type</th>
                    <th>Discount amount in %/Free product quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if($menuItemOfferList->isNotEmpty())
                    @foreach($menuItemOfferList as $item)
                        <tr>
                            <td class="align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle">{{ $item->offer_name }}</td>
                            <td class="align-middle">{{ $item->offer_description }}</td>
                            <td class="align-middle">
                                @if($item->offer_type == '0')
                                    Discount
                                @elseif($item->offer_type == '1')
                                    Buy 1 get Product(s) free
                                @endif
                            </td>
                            <td class="align-middle">
                                @if($item->offer_type == '0')
                                    {{ $item->discount_amount }}% Discount
                                @elseif($item->offer_type == '1')
                                    {{ $item->discount_amount }}₹ Off
                                @elseif($item->offer_type == '2')
                                    Buy 1 get {{ $item->products_free_quantity }} Product(s) free
                                @endif
                            </td>
                            <td class="align-middle">
                                <a href="{{ route('admin.hotel.menu-item.offer.edit', ['hotelId'=>$hotelId, 'productId'=>$menuItem->id, 'id'=>$item->id]) }}" class="btn btn-primary"><i class="fa fa-edit"></i> Edit</a>
                                <form method="POST" id="delete-data-form" action="{{ route('admin.hotel.menu-item.offer.delete', ['hotelId'=>$hotelId, 'productId'=>$menuItem->id, 'id'=>$item->id]) }}" hidden>
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                </form>
                                <button type="button" class="btn btn-danger" id="delete-item"><i class="fa fa-trash"></i> Delete</button>
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
