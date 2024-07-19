@extends('layouts.admin.layout')
@section('section')
<!-- <body> -->
<!-- Main content -->
<div class="container-fluid">

    <!-- Row Starts -->
    <div class="row">
        <div class="col-sm-12 p-0">
            <div class="main-header">
                <h4>Manage Users</h4>
                <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="icofont icofont-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Manage Users</a>
                    </li>
                    <li class="breadcrumb-item"><a>List</a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
    <!-- Row end -->

    <section class="content">
        <div class="row">
            <div class="col-12">
                <div class="card" style="padding: 20px;">
                    <form action="{{ route('manageusers.list') }}" class="form-horizontal" method="get" onsubmit="return filterFormValidate();">
                        <div class="col-md-4 mb30">
                            <select class="form-control col-md-7 col-xs-12" name="searchstate" id="searchstate">
                                <option value="">Select State</option>
                                @foreach ($getAllState as $state)
                                <option value="{{ $state->id }}" {{ old('searchstate', request('searchstate')) == $state->id ? 'selected' : '' }}>
                                    {{ $state->state }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb30">
                            <select name="searchlocal" id="searchlocal" class="form-control col-md-7 col-xs-12">
                                <option value="">Select Local constituency Area</option>
                                @foreach ($localConList as $localConListDet)
                                <option value="{{ $localConListDet->id }}" {{ old('searchlocal', request('searchlocal')) == $localConListDet->id ? 'selected' : '' }}>
                                    {{ $localConListDet->lga }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb30">
                            <select class="form-control col-md-7 col-xs-12" name="searchtype" id="searchtype">
                                <option value="">Select Campaign Type</option>
                                <option value="1" {{ old('searchtype', request('searchtype')) == 1 ? 'selected' : '' }}>President</option>
                                <option value="2" {{ old('searchtype', request('searchtype')) == 2 ? 'selected' : '' }}>Senate</option>
                                <option value="3" {{ old('searchtype', request('searchtype')) == 3 ? 'selected' : '' }}>House of Representative</option>
                                <option value="4" {{ old('searchtype', request('searchtype')) == 4 ? 'selected' : '' }}>Governor</option>
                                <option value="5" {{ old('searchtype', request('searchtype')) == 5 ? 'selected' : '' }}>House of Assembly</option>
                                <option value="6" {{ old('searchtype', request('searchtype')) == 6 ? 'selected' : '' }}>Local Government Chairman</option>
                                <option value="7" {{ old('searchtype', request('searchtype')) == 7 ? 'selected' : '' }}>Councilor</option>
                            </select>
                        </div>
                        <div class="col-md-4" style="margin-top: 10px;">
                            <input type="search" name="searchtxt" id="searchtxt" placeholder="Search by User Name or Email" value="{{ old('searchtxt', request('searchtxt')) }}" class="form-control">
                        </div>
                        <div class="col-md-1" style="margin-top: 10px;">
                            <input type="submit" name="search" id="search" value="Filter" class="btn btn-info search">
                        </div>
                        <div class="col-md-1" style="margin-top: 10px;">
                            <input type="button" value="Reset" class="btn btn-info search" onclick="location.href='{{ route('manageusers.list') }}'">
                        </div>
                    </form>


                    <!-- /.card-header -->
                    <div class="x_content">
                        @if($userRes->isEmpty())
                        <div class="col-md-12 text-center">
                            <h4>No Users found</h4>
                        </div>
                        @else
                        @php($i=0)
                        @foreach ($userRes as $val)
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="well profile_view">
                                <div class="col-sm-12">
                                    <div class="left col-xs-7">
                                        <h2>{{ stripslashes(ucfirst($val->first_name)) . ' ' . stripslashes($val->last_name) }}</h2>
                                        <ul class="list-unstyled">
                                            <li><i class="fa fa-type"></i> {{ stripslashes($val->user_type) }}</li>
                                            <li><i class="fa fa-user"></i> {{ stripslashes($val->userid) }}</li>
                                            <li><i class="fa fa-envelope"></i> {{ stripslashes($val->email_id) }}</li>
                                            <li><i class="fa fa-phone"></i> {{ stripslashes($val->telephone) }}</li>
                                        </ul>
                                    </div>
                                    <div class="right col-xs-5 text-center">
                                        @if ($val->profile_image != "")
                                        <img src="{{ asset('/images/users/' . $val->profile_image) }}" alt="" class="img-circle img-responsive">
                                        @else
                                        <img src="{{ asset('/images/user.png') }}" alt="" class="img-circle img-responsive">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xs-12 bottom">
                                    <div class="col-xs-12 col-sm-5 emphasis">
                                        Status:
                                        @if ($val->is_active == 1)
                                        <a href="javascript:void(0);" data-id="{{ $val->id }}" data-status="0" class="status-link"><span class="btn btn-success btn-xs">Active</span></a>
                                        @elseif ($val->is_active == 0)
                                        <a href="javascript:void(0);" data-id="{{ $val->id }}" data-status="1" class="status-link"><span class="btn btn-warning btn-xs">Inactive</span></a>
                                        @endif
                                        <br>
                                        Mail Verified:
                                        @if ($val->is_mail_verified == 1)
                                        <span class="btn btn-success btn-xs" style="cursor: default !important;">YES</span>
                                        @else
                                        <a href="#"><span class="btn btn-warning btn-xs">Verify Email</span></a>
                                        @endif
                                    </div>

                                    <div class="col-xs-12 col-sm-7 emphasis">
                                        <a class="btn btn-success btn-xs" href="#" data-toggle="modal" data-target="#view{{ $val->id }}" title="View" style="padding: 5px;">
                                            <i class="fa fa-file-text" aria-hidden="true"> View</i>
                                        </a>
                                        <a class="btn btn-info btn-xs" href="#" title="Edit" style="padding: 5px;">
                                            <i class="fa fa-edit"> Edit</i>
                                        </a>
                                        <a class="btn btn-danger btn-xs" href="{{ route('manageusers.destroy', $val->id) }}" onclick="return confirm('Are you sure to delete!');" title="Delete" style="padding: 5px;">
                                            <i class="fa fa-trash"> Delete</i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="view{{ $val->id }}" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                        <h4 class="modal-title" id="myModalLabel">User Detail</h4>
                                    </div>
                                    <div class="modal-body" style="padding:0px;">
                                        <div class="col-md-12">
                                            <div class="">
                                                <div class="control-group">
                                                    <label for="name" class="col-lg-3 control-label"><b>First Name :</b></label>
                                                    <div class="col-lg-9">
                                                        {{ ucwords($val->first_name) }}
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="control-group">
                                                    <label for="slug" class="col-lg-3 control-label"><b>Last Name :</b></label>
                                                    <div class="col-lg-9">
                                                        {{ $val->last_name }}
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="control-group">
                                                    <label for="description" class="col-lg-3 control-label"><b>Username :</b></label>
                                                    <div class="col-lg-9">
                                                        {{ stripslashes($val->username) }}
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="control-group">
                                                    <label for="description" class="col-lg-3 control-label"><b>Email-Id :</b></label>
                                                    <div class="col-lg-9">
                                                        {{ stripslashes($val->email_id) }}
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>

                                                <div class="control-group">
                                                    <label for="image" class="col-lg-3 control-label"><b>Contact Number :</b></label>
                                                    <div class="col-lg-9">
                                                        {{ stripslashes($val->telephone) }}
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>

                                                <div class="control-group">
                                                    <label for="status" class="col-lg-3 control-label"><b>Status :</b></label>
                                                    <div class="col-lg-9">
                                                        @if ($val->is_active == 1)
                                                        <span class="label label-success">Active</span>
                                                        @else
                                                        <span class="label label-warning">Inactive</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="control-group">
                                                    <label for="created" class="col-lg-3 control-label"><b>Created Date :</b></label>
                                                    <div class="col-lg-9">
                                                        {{ date('m-d-Y', strtotime($val->created_at)) }}
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                                <div class="control-group">
                                                    <label for="modified" class="col-lg-3 control-label"><b>Modified Date :</b></label>
                                                    <div class="col-lg-9">
                                                        @if (!$val->updated_at)
                                                        ....
                                                        @else
                                                        {{ date('m-d-Y', strtotime($val->updated_at)) }}
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div>
                        @endforeach
                        @endif
                    </div>
                    <!-- Pagination links -->
                    <ul class="pagination pull-right">
                        {{ $userRes->appends(request()->query())->links() }}
                    </ul>

                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('.status-link').click(function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var status = $(this).data('status');
            var url = '{{ route("manageusers.status", ["id" => ":id", "status" => ":status"]) }}';
            url = url.replace(':id', id).replace(':status', status);

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status == 'success') {
                        location.reload();
                    } else {
                        alert('Failed to update status');
                    }
                },
                error: function(response) {
                    alert('Error: ' + response.responseText);
                }
            });
        });
    });
</script>

<style>
    .profile_view {
        margin-bottom: 20px;
        display: inline-block;
        width: 100%;
    }

    .well.profile_view {
        padding: 10px 0 0;
    }

    .well.profile_view .divider {
        border-top: 1px solid #e5e5e5;
        padding-top: 5px;
        margin-top: 5px;
    }

    .well.profile_view .ratings {
        margin-bottom: 0;
    }

    .well.profile_view {
        background: #fff;
    }

    .well.profile_view .bottom {
        margin-top: 0px;
        background: #F2F5F7;
        padding: 9px 0;
        border-top: 1px solid #E6E9ED;
    }

    .well.profile_view .left {
        margin-top: 20px;
    }

    .well.profile_view .left p {
        margin-bottom: 3px;
    }

    .well.profile_view .right {
        margin-top: 0px;
        padding: 10px;
    }

    .well.profile_view .img-circle {
        border: 1px solid #E6E9ED;
        padding: 2px;
    }

    .well.profile_view h2 {
        margin: 5px 0;
    }

    .well.profile_view .ratings {
        text-align: left;
        font-size: 16px;
    }

    .well.profile_view .brief {
        margin: 0;
        font-weight: 300;
    }

    .profile_left {
        background: white;
    }
</style>
@endsection