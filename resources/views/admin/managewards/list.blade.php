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
                        {{-- <form action="{{ route('manageward.list') }}" id="filter-form" method="get"
                            class="form-horizontal">
                            <div class="col-sm-3">
                                <input type="search" name="searchtxt" id="searchtxt" placeholder=" Search by Wards"
                                    class="form-control">
                            </div>
                            <div class="col-sm-1">
                                <button type="button" id="filter-btn" class="btn btn-info search">Filter</button>
                            </div>
                            <div class="col-sm-1">
                                <button type="button" id="reset-btn" class="btn btn-info search"
                                    onclick="resetPage()">Reset</button>
                            </div>
                        </form> --}}
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="wardTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>SL#</th>
                                        <th>State</th>
                                        <th>Ward Name</th>
                                        <th>Ward No</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
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
            var table = $('#wardTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('manageward.getlist') }}",
                    type: 'GET',
                    data: function(d) {
                        d.searchtxt = $('#searchtxt').val();
                    }
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'state',
                        name: 'state'
                    },
                    {
                        data: 'ward_details',
                        name: 'ward_details'
                    },
                    {
                        data: 'ward_no',
                        name: 'ward_no'
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
            });

            $('#filter-btn').click(function() {
                table.draw();
            });

            $('#reset-btn').click(function() {
                $('#searchtxt').val('');
                table.draw();
            });
        });
    </script>
@endsection
