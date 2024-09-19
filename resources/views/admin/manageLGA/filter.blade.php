
@extends('layouts.admin.layout')
@section('section')
<!-- <body> -->
     <!-- Main content --> 
 <div class="container-fluid">
 
            <!-- Row Starts -->
            <div class="row">
                <div class="col-sm-12 p-0">
                    <div class="main-header">
                    <h4>Manage Local Government Area</h4>
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
          <form id="filter-form" method="get" class="form-horizontal">
          <div class="col-sm-3">
                <input type="search" name="lga" id="lga" placeholder=" Search by LGA" value="" class="form-control">
            </div>
            <div class="col-sm-3">
            <select name="state" id="state" class="form-control">
                <option value="">Select State</option>
                @foreach (App\Models\State::all() as $statedata )
                    <option value="{{$statedata->id }}">{{$statedata->state }}</option>
                @endforeach
            </select>
            </div>
            <div class="col-sm-1">
                <button type="button" id="filter-btn" class="btn btn-info search">Filter</button>
            </div>
            <div class="col-sm-1">
                <button type="button" id="reset-btn" class="btn btn-info search" onclick="resetPage()">Reset</button>
            </div>
         </form>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="datatable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>SL#</th>
                  <th>State</th>
                  <th>LGA</th>
                  <th>Code</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @php($i=0)
                @foreach ($localcont as $state)
                @php($stateid = App\Models\State::where('id',$state->state_id)->first())
                 <tr>
                  <td>{{++$i}}</td>
                  <td>{{$stateid->state}}</td>
                  <td>{{$state->lga}}</td>
                  <td>{{date('d-M-Y',strtotime($state->created_at))}}</td>
                  <td class="center">
                      <a class="btn btn-info" href="{{route('managelga.edit',$state->id)}}" title="Edit"><i class="fa fa-edit"></i></a>
                    <a class="btn btn-danger" href="{{route('managelga.destroy',$state->id)}}" onclick="return confirm('Are you sure to delete!');" title="Delete"><i class="fa fa-remove"></i></a>
                   </td>
                </tr>
                 @endforeach
              
                </tbody>
                <tfoot>
                <tr>
                  <th>SL#</th>
                  <th>State</th>
                  <th>State Constituency Name</th>
                  <th>Code</th>
                  <th>Composition</th>
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
    document.addEventListener('DOMContentLoaded', function() {
        const filterButton = document.getElementById('filter-btn');
        const resetButton = document.getElementById('reset-btn');
        const searchInput = document.getElementById('searchtxt');

        filterButton.addEventListener('click', function() {
            const searchTerm = searchInput.value;

            $.ajax({
                url: "{{ route('managelga.list') }}",
                method: "GET",
                data: { searchtxt: searchTerm },
                success: function(response) {
                    $('#party-list').html(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        resetButton.addEventListener('click', function() {
            searchInput.value = '';
            $.ajax({
                url: "{{ route('managelga.list') }}",
                method: "GET",
                data: { searchtxt: '' },
                success: function(response) {
                    $('#party-list').html(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

<script>
    function resetPage() {
        location.reload();
    }
</script>

<!-- </body> -->
<!-- </html> -->
@endsection
