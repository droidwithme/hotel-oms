@extends('pages.hotel.injector.injector')

@section('sub-title')
    All Orders
@endsection

@section('sub-custom-styles')
@endsection

@section('sub-custom-scripts')
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
                        {{ $order->order_status }}
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
@endsection
