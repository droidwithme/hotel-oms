@extends('pages.admin.injector.injector')

@section('sub-title')
    All Orders
@endsection

@section('sub-custom-styles')
@endsection

@section('sub-custom-scripts')
    <script>
        $(document).ready(function(){

            /**
             * for showing delete item popup
             */

            $(document).on('click',"#order-status",function(){
                $(this).addClass('selected-order-to-update');
                var message = $(this).data('message');
                var options = {'backdrop':'static'};
                $('#order-status-modal').modal(options);
                $("#order-status-modal-label").text('Order status');
                $("#modal-body-content h4").text(message)
            })

            // on click of confirmation
            $(document).on('click',"#order-status-action-confirm",function(){
                var el = $('.selected-order-to-update');
                var orderId = el.data('order-id');
                var orderStatus = el.data('order-status');

                $.ajax({
                    type: 'POST',
                    url: "{{ route('admin.order.status.update') }}",
                    data : {"_token": "{{ csrf_token() }}", 'order-id': orderId, 'order-status': orderStatus },
                    cache: false,
                    success: function(json){
                        if(json.status){
                            if (orderStatus === "proceeded"){
                                el.replaceWith('<button type="button" class="btn btn-primary m-1" id="order-status" data-order-id="'+orderId+'" data-order-status="completed" data-message="Are you sure you want to mark this order as completed?">Mark as completed</button>')
                            } else if(orderStatus === "completed"){
                                el.replaceWith('Order Completed')
                            }

                            $('#order-status-modal').modal('hide');
                        }
                    }
                })
            })

            //  on modal hide
            $('#order-status-modal').on('hide.bs.modal',function(){
                $('.selected-order-to-update').removeClass('selected-order-to-update')
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
                    <h1 class="text-primary mr-auto">Order</h1>
                </div>
            </div>
        </div>
        <!-- /heading -->
        <!-- table -->
        <table class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead class="thead-dark">
            <tr>
                <th>Order Id</th>
                <th>Order Date</th>
                <th>Store</th>
                <th>Store Mobile Number</th>
                <th>User</th>
                <th>User Mobile Number</th>
                <th>Alternate Mobile Number</th>
                <th>Address</th>
                <th>Customer Instructions</th>
                <th>Order Total</th>
                <th>Order Status</th>
            </tr>
            </thead>
            <tbody>
            @if(isset($order))
                <tr>
                    <td class="align-middle">{{ $order->order_id }}</td>
                    <td class="align-middle">{{ date('dS F Y h:i:s A', strtotime($order->order_date)) }}</td>
                    <td class="align-middle">{{ $order->hotel_name }}</td>
                    <td class="align-middle">{{ $order->hotel_mobile }}</td>
                    <td class="align-middle">{{ $order->api_user_name }}</td>
                    <td class="align-middle">{{ $order->api_user_mobile }}</td>
                    <td class="align-middle">
                        @if(isset($order->alternate_mobile ))
                        {{ $order->alternate_mobile }}
                        @else
                        &mdash;
                        @endif
                    </td>
                    <td class="align-middle">{{ $order->address }}</td>
                    <td class="align-middle">{{ $order->customer_instructions }}</td>
                    <td class="align-middle">{{ $order->order_total }} Rs.</td>
                    <td class="align-middle">
                        @if($order->order_status == "received")
                            <button type="button" class="btn btn-primary m-1" id="order-status" data-order-id="{{ $order->id }}" data-order-status="proceeded" data-message="Are you sure you want to mark this order as proceeded?">Proceed with order</button>
                        @elseif($order->order_status == "proceeded")
                            <button type="button" class="btn btn-primary m-1" id="order-status" data-order-id="{{ $order->id }}" data-order-status="completed" data-message="Are you sure you want to mark this order as completed?">Mark as completed</button>
                        @elseif($order->order_status == "completed")
                            Order Completed
                        @endif
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
        <!-- /table -->
        <!-- heading -->
        <div class="container-fluid mt-5">
            <div class="row">
                <div class="col">
                    <h1 class="text-primary mr-auto">Order Details</h1>
                </div>
            </div>
        </div>
        <!-- /heading -->
        <!-- table -->
        <table class="table table-striped table-bordered" id="myTable" cellspacing="0" width="100%">
            <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            </thead>
            <tbody>
            @if($orderDetails->isNotEmpty())
                @foreach($orderDetails as $item)
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle word-break">{{ $item->menu_item_name }}</td>
                        <td class="align-middle">{{ $item->amount_ordered }}</td>
                        <td class="align-middle">{{ $item->price }} Rs.</td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
        <!-- /table -->
    </div>
    <!-- Order Status Modal -->
    <div class="modal fade" id="order-status-modal" tabindex="-1" role="dialog" aria-labelledby="order-status-modal-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="delete-modal-label">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-body-content">
                    <h4 class="text-center">Message</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" id="order-status-action-confirm" class="btn btn-primary">Yes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Delete Modal -->
@endsection
