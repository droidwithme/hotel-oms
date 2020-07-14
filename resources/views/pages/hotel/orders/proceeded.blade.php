@extends('pages.hotel.injector.injector')

@section('sub-title')
    Proceeded Orders
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
                                {{ $item->order_status }}
                            </td>
                            <td class="align-middle">
                                <a href="{{ route('hotel.order.detail', $item->id) }}" class="btn btn-primary m-1"><i class="fa fa-eye"></i> View Details</a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <!-- /table -->
    </div>
@endsection
