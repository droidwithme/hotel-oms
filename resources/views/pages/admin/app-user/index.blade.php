@extends('pages.admin.injector.injector')

@section('sub-title')
    App Users
@endsection

@section('sub-custom-styles')
@endsection

@section('sub-custom-scripts')
    <script>
        $(document).ready(function(){

            /**
             * for showing show password popup
             */
            $(document).on('click',"#show-password",function(){
                $(this).addClass('show-password-trigger-clicked');

                var options = {'backdrop':'static'};
                $('#show-password-modal').modal(options);
                $("#show-password-modal-label").text('Show Password')
            })

            // on submission of password
            $(document).on('click', "#show-password-action-confirm", function(){
                var el = $(this);
                var trigger = $(".show-password-trigger-clicked");
                var appUserId = trigger.data('id');
                var token = '{{ csrf_token()}}';
                var adminPassword = $("#show-password-field").val();

                if(adminPassword !== "" && adminPassword.length > 0) {
                    el.parent(".modal-footer").hide();
                    $(".empty-field-error").hide();
                    $.ajax({
                        type: "POST",
                        url: "{{route('admin.app-user.password.view')}}",
                        data: {'_token': token, 'app-user-id': appUserId, 'password': adminPassword},
                        cache: false,
                        success: function (data) {
                            var status = data.status;
                            $(".show-password-form").hide();
                            if (status) {

                                if(data.password !== "" && data.password !== null){
                                    $(".show-password-success").show().html('Password : ' + data.password)
                                } else {
                                    $(".show-password-success").show().html('Please change the password of the store before doing this operation')
                                    setTimeout(function(){
                                        $('#show-password-modal').modal('hide')
                                    }, 5000)
                                }
                            } else {
                                $(".show-password-error").show();
                                setTimeout(function(){
                                    $('#show-password-modal').modal('hide')
                                }, 5000)
                            }
                        }
                    })
                } else {
                    $(".empty-field-error").show();
                }
            })

            $('#show-password-modal').on('show.bs.modal',function(){
                $(".show-password-form").show();
                $(".show-password-error").hide();
                $(".show-password-success").empty().hide();
                $(".empty-field-error").hide();
                $("#show-password-action-confirm").parent(".modal-footer").show()
                $("#show-password-field").val('')
            })

            //  on modal hide
            $('#show-password-modal').on('hide.bs.modal',function(){
                $('.show-password-trigger-clicked').removeClass('show-password-trigger-clicked')
            })

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
                    <h1 class="mr-auto" style="color: #009688">App Users</h1>
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
                    <th>Password</th>
                    <th>Mobile</th>
                    <th>Address</th>
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
                            <td class="align-middle">
                                <button type="button" class="btn btn-primary m-1" id="show-password" data-id="{{ $item->id  }}"><i class="fa fa-eye"></i> View Password</button>
                            </td>
                            <td class="align-middle">{{ $item->mobile }}</td>
                            <td class="align-middle">{{ $item->address }}</td>
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
                                <form method="POST" id="delete-data-form" action="{{ route('admin.app-user.delete', $item->id) }}" hidden>
                                    {{ csrf_field() }}
                                    {{ method_field('DELETE') }}
                                </form>
                                <button type="button" class="btn btn-danger m-1" id="delete-item"><i class="fa fa-trash"></i> Delete</button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <!-- /table -->
    </div>
    <!-- Show Password Modal -->
    <div class="modal fade" id="show-password-modal" tabindex="-1" role="dialog" aria-labelledby="show-password-modal-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="show-password-modal-label">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-body-content">
                    <form class="form-horizontal show-password-form m-0" onsubmit="return false;">
                        <!-- password -->
                        <div class="form-group mb-0">
                            <label class="col-form-label" for="password">Enter Admin Password Given To You</label>
                            <input type="password" name="password" class="form-control" id="show-password-field">
                            <span class="text-danger empty-field-error" style="display: none;">This Field Is Required</span>
                        </div>
                        <!-- /password -->
                    </form>
                    <!-- error -->
                    <h4 class="text-center show-password-error" style="display: none;">Wrong Admin Password Provided <span style="font-size: 16px; display: block;">This dialog wil dismiss in 5 seconds</span></h4>
                    <!-- success --->
                    <h4 class="text-center show-password-success" style="display: none;">Password : <span class="pass">TestPassword</span></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" id="show-password-action-confirm" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!-- /Show Password Modal -->
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
