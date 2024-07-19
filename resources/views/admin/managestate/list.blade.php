@extends('layouts.admin.layout')
@section('section')
<!-- <body> -->
<!-- Main content -->
<div class="container-fluid">

    <!-- Row Starts -->
    <div class="row">
        <div class="col-sm-12 p-0">
            <div class="main-header">
                <h4>Manage State</h4>
                <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="icofont icofont-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Manage State</a>
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
                    <a href="{{route('managestate.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> </a>
                    <!-- <form id="filter-form" method="get" class="form-horizontal">


                        <div class="col-sm-4">
                            <input type="search" name="searchtxt" id="searchtxt" placeholder=" Search by state" value="" class="form-control">
                        </div>
                        <div class="col-sm-1">
                            <button type="button" id="filter-btn" class="btn btn-info search">Filter</button>
                        </div>
                        <div class="col-sm-1">
                            <button type="button" id="reset-btn" class="btn btn-info search" onclick="resetPage()">Reset</button>
                        </div>
                    </form> -->
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>SL#</th>
                                    <th>State</th>
                                    <th>Zone</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i=0)
                                @foreach ($statelist as $state)
                                <tr>
                                    <td>{{++$i}}</td>
                                    <td>{{$state->state}}</td>
                                    <td>{{$state->zone}}</td>
                                    <!-- <td>
                  <img class="img-fluid mt-2" style="height: 100px;width: 165px;" src="{{ asset('images/parties/' . $state->state_img) }}" alt="Preview">
                  </td> -->
                                    <td>{{date('d-M-Y',strtotime($state->created_at))}}</td>
                                    <td class="center">
                                        <a class="btn btn-info" href="{{route('managestate.edit',$state->id)}}" title="Edit"><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-danger" href="{{route('managestate.destroy',$state->id)}}" onclick="return confirm('Are you sure to delete!');" title="Delete"><i class="fa fa-remove"></i></a>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>SL#</th>
                                    <th>State</th>
                                    <th>Zone</th>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButton = document.getElementById('filter-btn');
        const resetButton = document.getElementById('reset-btn');
        const searchInput = document.getElementById('searchtxt');

        filterButton.addEventListener('click', function() {
            const searchTerm = searchInput.value;

            $.ajax({
                url: "{{ route('managestate.list') }}",
                method: "GET",
                data: {
                    searchtxt: searchTerm
                },
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
                url: "{{ route('managestate.list') }}",
                method: "GET",
                data: {
                    searchtxt: ''
                },
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