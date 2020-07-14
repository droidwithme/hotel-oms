@extends('pages.admin.injector.injector')

@section('sub-title')
    App Users - Deleted
@endsection

@section('sub-custom-styles')
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
    <div class="main-container container">
        @include('layouts.messages')
        <!-- heading -->
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h1 class="mr-auto" style="color: #009688">Deleted App Users</h1>
                </div>
            </div>
        </div>
        <!-- /heading -->
        <!-- table -->
            <table class="table table-striped table-bordered" id="myTable" cellspacing="0" width="100%">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Profile Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @if($apiUserList->isNotEmpty())
                @foreach($apiUserList as $item)
                    <tr>
                        <td class="align-middle">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $item->name }}</td>
                        <td class="align-middle">
                            @if($item->email)
                            {{ $item->email }}
                            @else
                            &mdash;
                            @endif
                        </td>
                        <td class="align-middle">{{ $item->mobile }}</td>
                        <td class="align-middle">
                            @if($item->lat)
                            {{ $item->lat }}
                            @else
                            &mdash;
                            @endif
                        </td>
                        <td class="align-middle">
                            @if($item->long)
                            {{ $item->long }}
                            @else
                            &mdash;
                            @endif
                        </td>
                        <td class="align-middle">
                            @if(isset($item->profile_picture_path))
                                <img src="{{ asset('assets/images/api-user-images/'.$item->profile_picture_path) }}" style="width: 100px; height: 100px;">
                            @else
                                &mdash;
                            @endif
                        </td>
                        <td class="align-middle">
                            <form method="POST" id="restore-data-form" action="{{ route('admin.app-user.deleted.restore', $item->id) }}" hidden>
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
