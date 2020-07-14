@extends('pages.admin.injector.injector')

@section('sub-title')
    Proceeded Orders
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
                            if(orderStatus === "completed"){
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
                    <h1 class="text-primary mr-auto">Proceeded Orders</h1>
                </div>
            </div>
        </div>
        <!-- /heading -->
        <!-- table -->
        <table class="table table-striped table-bordered" id="myTable" cellspacing="0" width="100%">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
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
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if($orderList->isNotEmpty())
                    @foreach($orderList as $item)
                        <tr>
                            <td class="align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle">{{ $item->order_id }}</td>
                            <td class="align-middle">{{ date('dS F Y h:i:s A', strtotime($item->order_date)) }}</td>
                            <td class="align-middle">{{ $item->hotel_name }}</td>
                            <td class="align-middle">{{ $item->hotel_mobile }}</td>
                            <td class="align-middle">{{ $item->api_user_name }}</td>
                            <td class="align-middle">{{ $item->api_user_mobile }}</td>
                            <td class="align-middle">
                                @if(isset($item->alternate_mobile ))
                                {{ $item->alternate_mobile }}
                                @else
                                &mdash;
                                @endif
                            </td>
                            <td class="align-middle">{{ $item->address }}</td>
                            <td class="align-middle">{{ $item->customer_instructions }}</td>
                            <td class="align-middle">{{ $item->order_total }} Rs.</td>
                            <td class="align-middle">
                                @if($item->order_status == "proceeded")
                                    <button type="button" class="btn btn-primary m-1" id="order-status" data-order-id="{{ $item->id }}" data-order-status="completed" data-message="Are you sure you want to mark this order as completed?">Mark as completed</button>
                                @elseif($item->order_status == "completed")
                                    Order Completed
                                @endif
                            </td>
                            <td class="align-middle">
                                <a href="{{ route('admin.order.detail', $item->id) }}" class="btn btn-primary m-1"><i class="fa fa-eye"></i> View Details</a>
                            </td>
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
