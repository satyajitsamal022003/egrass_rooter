@extends('layouts.admin.layout')
@section('section')
<!-- <body> -->
<!-- Main content -->
<div class="container-fluid">
  <!-- Row Starts -->
  <div class="row">
    <div class="col-sm-12 p-0">
      <div class="main-header">
        <h4>Manage Menus</h4>
        <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="icofont icofont-home"></i></a>
          </li>
          <li class="breadcrumb-item"><a href="#">Manage Dashboard Menus</a>
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
                  <th>Menu Name</th>
                  <th>Menu Type</th>
                  <th>Parent</th>
                  <th>Menu Link</th>
                  <th>Order</th>
                  <th>Status</th>
                  <th>Created Date</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @php $count = 0; @endphp
                @foreach ($menus as $menu)
                @php $count++; @endphp
                <tr>
                  <td>{{ $count }}</td>
                  <td>{!! str_repeat('— ', $menu->level) . $menu->menu_name !!}</td>
                  <td>
                    @switch($menu->menu_type)
                    @case(1)
                    Add
                    @break
                    @case(2)
                    List
                    @break
                    @case(3)
                    Parent
                    @break
                    @default
                    None
                    @endswitch
                  </td>
                  <td>{{ $menu->select_parent == 0 ? 'None' : \App\Models\Dashboardmenu::find($menu->select_parent)->menu_name }}</td>
                  <td><a href="{{ $menu->menu_link }}">{{ $menu->menu_link }}</a></td>
                  <td class="center">{{ $menu->order_no }}</td>
                  <td class="status">
                    <label class="switch">
                      <input type="checkbox" onclick="catstatus({{$menu->id}})" <?php if ($menu->status == '1') {
                                                                                  echo "checked";
                                                                                } ?>>
                      <span class="slider"></span>
                    </label>
                  </td>
                  <td class="center">{{ date('d-m-Y', strtotime($menu->created)) }}</td>
                  <td class="center">
                    <a class="btn btn-info" href="{{ route('managedashboardmenu.edit', $menu->id) }}" title="Edit"><i class="fa fa-edit"></i></a>
                    <a class="btn btn-danger" href="{{ route('managedashboardmenu.destroy', $menu->id) }}" onclick="return confirm('Are you sure to delete!');" title="Delete"><i class="fa fa-remove"></i></a>
                  </td>
                </tr>
                @endforeach
              </tbody>
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
      url: "{{ route('managedashboardmenu.status')}}",
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