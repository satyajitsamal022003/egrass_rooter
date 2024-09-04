@extends('layouts.admin.layout')
@section('section')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<!-- <body> -->
<!-- Main content -->
<div class="container-fluid">

    <!-- Row Starts -->
    <div class="row">
        <div class="col-sm-12 p-0">
            <div class="main-header">
                <h4>Manage Team</h4>
                <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="icofont icofont-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Manage Team</a>
                    </li>
                    <li class="breadcrumb-item"><a>View Team Members</a>
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
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>SL#</th>
                                    <th>Role</th>
                                    <th>Name</th>
                                    <th>Phone Number</th>
                                    <th>Email ID</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Created</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @php($i=0)
                                @foreach ($members as $tl)
                                <tr>
                                    <td>{{++$i}}</td>
                                    <td>
                                        @php($rolename=App\Models\Role::where('id',$tl->role_type)->first())
                                        {{$rolename->role ?? ''}}
                                    </td>
                                    <td>{{$tl->name}}</td>
                                    <td>{{$tl->phone_number}}</td>
                                    <td>{{$tl->email_id}}</td>
                                    <td>{{$tl->address}}</td>
                                    <td class="status">
                                        <label class="switch">
                                            <input
                                                type="checkbox"
                                                onclick="catstatus({{ $tl->id }})"
                                                {{ $tl->is_active == 1 ? 'checked' : '' }}>
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                    <td>
                                        @php($username=App\Models\Campaign_user::where('id',$tl->user_id)->first()){{$username->first_name ?? ''}}</td>
                                    <td>
                                        @if (!empty($tl->created) && $tl->created != '0000-00-00 00:00:00')
                                        {{ date('d-M-Y', strtotime($tl->created)) }}
                                        @else
                                        N/A
                                        @endif
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>SL#</th>
                                    <th>Role</th>
                                    <th>Name</th>
                                    <th>Phone Number</th>
                                    <th>Email ID</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Created</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                            </tfoot>
                        </table>
                    </div>
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
    function catstatus(id) {
        $.ajax({
            url: "{{ route('manageteammember.status')}}",
            type: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                id: id
            },
            success: function(response) {
                if (response.code == 200) {
                    Swal.fire('Status Updated', '', 'success');
                }
            }
        })
    }
</script>
<!-- </body> -->
<!-- </html> -->
@endsection