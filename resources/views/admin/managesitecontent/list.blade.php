
@extends('layouts.admin.layout')
@section('section')
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
  background-color: #2196F3;
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



<!-- <body> -->
     <!-- Main content --> 
 <div class="container-fluid">
 
            <!-- Row Starts -->
            <div class="row">
                <div class="col-sm-12 p-0">
                    <div class="main-header">
                    <h4>Manage Site Content</h4>
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
                  <th>Reference</th>
                  <th>Description</th>
                  <th>Image</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    @php($i=0)
                @foreach ($localcont as $blog)
                @php($cleanDescription = strip_tags($blog->description))
                 <tr>
                  <td>{{++$i}}</td>
                  <td>{{$blog->title}}</td>
                  <td>{{$blog->referene}}</td>
                  <td>{{$cleanDescription}}</td>
                  <td>
                  @php( $imagePath = public_path('images/sitecontents/' . $blog->image))
                  @if (file_exists($imagePath) && !empty($blog->image))
                    <img class="img-fluid mt-2" style="height: 100px; width: 165px;" src="{{ asset('images/sitecontents/' . $blog->image) }}" alt="Preview">
                  @else
                    <p>N/A</p>
                  @endif
                  </td> 
                  <td class="status">
                  <label class="switch">
                  <input type="checkbox" onclick="catstatus({{$blog->id}})" <?php if($blog->is_active == '1' ) {echo "checked"; } ?>>
                  <span class="slider"></span>
                  </label>
                  </td>
                 
                  <td class="center">
                      <a class="btn btn-info" href="{{route('managesitecontent.edit',$blog->id)}}" title="Edit"><i class="fa fa-edit"></i></a>
                      <a class="btn btn-danger" href="{{route('managesitecontent.destroy',$blog->id)}}" onclick="return confirm('Are you sure to delete!');" title="Delete"><i class="fa fa-remove"></i></a>
                 </td>
                </tr>
              @endforeach
              
                </tbody>
                <tfoot>
                <tr>
                  <th>SL#</th>
                  <th>Title</th>
                  <th>Reference</th>
                  <th>Description</th>
                  <th>Image</th>
                  <th>Status</th>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
  $(document).ready(function() {
    // Change status button click event
    $(document).on('click', '.change-status-btn', function() {
        var url = $(this).data('url');
        var newStatus = $(this).data('status') === 'active' ? 'inactive' : 'active';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', status: newStatus },
            success: function(response) {
                // Handle success response
                console.log(response);
                $('#status-message').text('Status changed successfully!');
                // Update button text or status display if needed
                $(this).data('status', newStatus);
                $(this).text('Change Status to ' + newStatus);
            },
            error: function(xhr) {
                // Handle error response
                console.log(xhr.responseText);
                $('#status-message').text('Error occurred while changing status.');
            }
        });
    });

    // Filter button click event
    $('#filter-btn').click(function() {
        var formData = $('#filter-form').serialize();

        $.ajax({
            url: "{{ route('manageblog.filter') }}",
            type: 'GET',
            data: formData,
            success: function(response) {
                $('#datatable tbody').html(response);
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    });

    // Reset button click event
    $('#reset-btn').click(function() {
        $('#filter-form')[0].reset(); // Reset the form
        $('#filter-btn').click(); // Trigger filter button click to reset the table content
    });
});
</script>

<!-- filter -->
<script>
    $(document).ready(function() {
        $('#filter-btn').click(function() {
            var formData = $('#filter-form').serialize();

            $.ajax({
                url: "{{ route('manageblog.filter') }}",
                type: 'GET',
                data: formData,
                success: function(response) {
                    $('#datatable').html(response);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });

        $('#reset-btn').click(function() {
            $('#filter-form')[0].reset(); // Reset the form
            $('#filter-btn').click(); // Trigger filter button click to reset the table content
        });
    });
</script>


<script>
  function catstatus(id){
    $.ajax({
         url:"{{ route('managesitecontent.status')}}",
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
