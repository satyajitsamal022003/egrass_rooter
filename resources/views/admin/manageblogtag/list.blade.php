@extends('layouts.admin.layout')
@section('section')
<!-- <body> -->
<!-- Main content -->
<div class="container-fluid">

  <!-- Row Starts -->
  <div class="row">
    <div class="col-sm-12 p-0">
      <div class="main-header">
        <h4>Manage Blog Tag</h4>
        <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="icofont icofont-home"></i></a>
          </li>
          <li class="breadcrumb-item"><a href="#">Manage Blog Tag</a>
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
          <!-- /.card-header -->
          <div class="card-body">
            <table id="datatable" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>SL#</th>
                  <th>Tag Title</th>
                  <th>Status</th>
                  <th>Created</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @php($i=0)
                @foreach ($blogtaglist as $blogtag)
                <tr>
                  <td>{{++$i}}</td>
                  <td>{{ $blogtag->tag_title}}</td>
                  <td class="status">
                    <label class="switch">
                      <input type="checkbox" onclick="catstatus({{$blogtag->tag_id}})" <?php if ($blogtag->is_active == '1') {
                                                                                          echo "checked";
                                                                                        } ?>>
                      <span class="slider"></span>
                    </label>
                  </td>
                  <td>{{date('d-M-Y',strtotime( $blogtag->created_at))}}</td>
                  <td class="center">
                    <a class="btn btn-info" href="{{route('manageblogtag.edit',$blogtag->tag_id )}}" title="Edit"><i class="fa fa-edit"></i></a>
                    <a class="btn btn-danger" href="{{route('manageblogtag.destroy',$blogtag->tag_id )}}" onclick="return confirm('Are you sure to delete!');" title="Delete"><i class="fa fa-remove"></i></a>
                  </td>
                </tr>
                @endforeach

              </tbody>
              <tfoot>
                <tr>
                  <th>SL#</th>
                  <th>Tag Title</th>
                  <th>Status</th>
                  <th>Created</th>
                  <th>Actions</th>
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

<script>
  function catstatus(id) {
    $.ajax({
      url: "{{ route('manageblogtag.status')}}",
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
<!-- </html> -->
@endsection