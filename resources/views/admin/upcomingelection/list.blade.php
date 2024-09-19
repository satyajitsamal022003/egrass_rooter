@extends('layouts.admin.layout')
@section('section')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<!-- Main content -->
<div class="container-fluid">

    <!-- Row Starts -->
    <div class="row">
        <div class="col-sm-12 p-0">
            <div class="main-header">
                <h4>Manage Upcoming Elections</h4>
                <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="icofont icofont-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Manage Upcoming Elections</a>
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
                <a href="{{route('addupcomingelections')}}" class="btn btn-primary"><i class="fa fa-plus"></i> </a>
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>SL#</th>
                                    <th>Type of Election</th>
                                    <th>State</th>
                                    <th>Date of Election</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i=0)
                                @foreach ($elections as $election)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td>
                                        @if ($election->electionType)
                                        {{ $election->electionType->type }}
                                        @else
                                        N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if ($election->state)
                                        {{ $election->state->state }}
                                        @else
                                        N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if (!empty($election->election_date) && $election->election_date != '0000-00-00')
                                        {{ date('d-M-Y', strtotime($election->election_date)) }}
                                        @else
                                        N/A
                                        @endif
                                    </td>
                                    <td class="status">
                                        <label class="switch">
                                            <input type="checkbox" onclick="catstatus({{$election->id}})" <?php if ($election->is_active == '1') {
                                                                                                            echo "checked";
                                                                                                        } ?>>
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                    <td class="center">
                                        <a class="btn btn-info" href="{{ route('editupcomingelections', $election->id) }}" title="Edit"><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-danger" href="{{ route('destroyupcomingelection', $election->id) }}" onclick="return confirm('Are you sure to delete?');" title="Delete"><i class="fa fa-remove"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>SL#</th>
                                    <th>Type of Election</th>
                                    <th>State</th>
                                    <th>Date of Election</th>
                                    <th>Status</th>
                                    <th>Action</th>
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
      url: "{{ route('upcomingstatus')}}",
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


@endsection