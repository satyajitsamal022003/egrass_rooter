 
@extends('layouts.admin.layout')
@section('section')
<!-- <body> -->
  <!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<style>
  /* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 40px; /* Reduced width */
  height: 20px; /* Reduced height */
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px; /* Reduced height */
  width: 16px; /* Reduced width */
  left: 2px; /* Adjusted position */
  bottom: 2px; /* Adjusted position */
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #7066e0;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(20px); /* Adjusted translation */
  -ms-transform: translateX(20px); /* Adjusted translation */
  transform: translateX(20px); /* Adjusted translation */
}

/* Rounded sliders */
.slider.round {
  border-radius: 20px; /* Adjusted to match new height */
}

.slider.round:before {
  border-radius: 50%;
}

</style>
     <!-- Main content --> 
 <div class="container-fluid">
 
            <!-- Row Starts -->
            <div class="row">
                <div class="col-sm-12 p-0">
                    <div class="main-header">
                    <h4>Manage Feature</h4>
                    <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
                        <li class="breadcrumb-item"><a href="index.html"><i class="icofont icofont-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">Forms Components</a>
                        </li>
                        <li class="breadcrumb-item"><a href="form-elements-bootstrap.html">General Elements</a>
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
                  <th>Title</th>
                  <th>Slug</th>
                  <th>Feature Class</th>
                  <th>Description</th>
                  <th>Status</th>
                  <th>Created Date</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @php($i=0)
                @foreach ($featurelist as $feature)
                 <tr>
                  <td>{{++$i}}</td>
                  <td>{{$feature->title}}</td>
                  <td>{{$feature->slug}}</td>
                  <td><i class="fa fa-<?php echo $feature['icon'];?>"></td>
                  <td>{{$feature->description}}</td>
                  <td class="status">
                  <label class="switch">
                  <input type="checkbox" onclick="catstatus({{$feature->id}})" <?php if($feature->is_active == '1' ) {echo "checked"; } ?>>
                  <span class="slider"></span>
                  </label>
                  </td>
                  <td>  @if (!empty($feature->created_at) && $feature->created_at != '0000-00-00 00:00:00')
                        {{ date('d-M-Y', strtotime($feature->created_at)) }}
                        @else 
                            N/A
                        @endif
                  </td>
                  <td class="center">
                      <a class="btn btn-info" href="{{route('managefeature.edit',$feature->id )}}" title="Edit"><i class="fa fa-edit"></i></a>
                      <a class="btn btn-danger" href="{{route('managefeature.destroy',$feature->id )}}" onclick="return confirm('Are you sure to delete!');" title="Delete"><i class="fa fa-remove"></i></a>
                  </td>
                </tr>
              @endforeach
              
                </tbody>
                <tfoot>
                <tr>
                  <th>SL#</th>
                  <th>Title</th>
                  <th>Slug</th>
                  <th>Feature Class</th>
                  <th>Description</th>
                  <th>Status</th>
                  <th>Created Date</th>
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
  function catstatus(id){
    $.ajax({
         url:"{{ route('managefeature.status')}}",
         type:"POST",
         data: {
        "_token": "{{ csrf_token() }}",
        id: id
        },
         success :function(response){
          if (response.code == 200) {
            Swal.fire('Status Updated','' ,'success');
            } 
         }
    })
}
</script>
<!-- </body> -->
<!-- </html> -->
@endsection
