
@extends('layouts.admin.layout')
@section('section')
<!-- <body> -->
     <!-- Main content --> 
 <div class="container-fluid">
 
            <!-- Row Starts -->
            <div class="row">
                <div class="col-sm-12 p-0">
                    <div class="main-header">
                    <h4>Manage Wards</h4>
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
                <input type="search" name="searchtxt" id="searchtxt" placeholder=" Search by Wards" class="form-control">
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
                  <th>Ward Name</th>
                  <th>Ward No</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @php($i=0)
                @foreach ($localcont as $state)
                @php($stateid = App\Models\Local_constituency::where('id',$state->lga_id)->first())
                 <tr>
                  <td>{{++$i}}</td>
                  <td>{{$stateid->lga}}</td>
                  <td>{{$state->ward_details}}</td>
                  <td>{{$state->ward_no}}</td>
                  <td class="center">
                      <a class="btn btn-info" href="{{route('manageward.edit',$state->id)}}" title="Edit"><i class="fa fa-edit"></i></a>
                    <a class="btn btn-danger" href="{{route('manageward.destroy',$state->id)}}" onclick="return confirm('Are you sure to delete!');" title="Delete"><i class="fa fa-remove"></i></a>
                   </td>
                </tr>
                 @endforeach
              
                </tbody>
                <tfoot>
                <tr>
                  <th>SL#</th>
                  <th>State</th>
                  <th>Ward Name</th>
                  <th>Ward No</th>
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
                url: "{{ route('manageward.list') }}",
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
                url: "{{ route('manageward.list') }}",
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
