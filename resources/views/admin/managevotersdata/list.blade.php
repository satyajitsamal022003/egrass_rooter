@extends('layouts.admin.layout')
@section('section')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>


<style>
    /* The switch - the box around the slstateer */
    .switch {
        position: relative;
        display: inline-block;
        width: 40px;
        /* Reduced width */
        height: 20px;
        /* Reduced height */
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
        height: 16px;
        /* Reduced height */
        width: 16px;
        /* Reduced width */
        left: 2px;
        /* Adjusted position */
        bottom: 2px;
        /* Adjusted position */
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #2196F3;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked+.slider:before {
        -webkit-transform: translateX(20px);
        /* Adjusted translation */
        -ms-transform: translateX(20px);
        /* Adjusted translation */
        transform: translateX(20px);
        /* Adjusted translation */
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 20px;
        /* Adjusted to match new height */
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
                <h4>Manage Voters Data</h4>
                <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="icofont icofont-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Manage Voters Data</a>
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
                    <form id="filterForm" class="form-horizontal">
                        @csrf
                        <div class="col-md-3 mb30">
                            <select id="searchage" name="searchage" class="form-control">
                                <option value="">--Search by Age--</option>
                                <option value="1">18-20</option>
                                <option value="2">21-30</option>
                                <option value="3">31-40</option>
                                <option value="4">41-50</option>
                                <option value="5">51-60</option>
                                <option value="6">61-70</option>
                                <option value="7">71-80</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb30">
                            <select id="searchemp" name="searchemp" class="form-control">
                                <option value="">--Search by Employment--</option>
                                @if (!empty($getAllemp) && count($getAllemp) > 0)
                                @foreach ($getAllemp as $getemp)
                                <option value="{{ $getemp->employ_status }}" {{ request('searchemp') == $getemp->employ_status ? 'selected' : '' }}>
                                    {{ stripslashes($getemp->employ_status) }}
                                </option>
                                @endforeach
                                @else
                                <option value="developer">Developer</option>
                                <option value="engineer">Engineer</option>
                                <option value="manager">Manager</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-md-3 mb30">
                            <select id="searchgender" name="searchgender" class="form-control">
                                <option value="">--Search by Gender--</option>
                                <option value="female">Female</option>
                                <option value="male">Male</option>
                                <option value="others">Others</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb30">
                            <select id="state" name="state" class="form-control">
                                <option value="">--Search by State--</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb30">
                            <select name="senatorial_state" id="senatorial_state" class="form-control">
                                <option value="">--Search by Senatorial State--</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb30">
                            <select name="federal_constituency" id="federal_constituency" class="form-control">
                                <option value="">--Search by Federal Constituency--</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb30">
                            <select name="local_constituency" id="local_constituency" class="form-control">
                                <option value="">--Search by LGA--</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb30">
                            <select name="ward" id="ward" class="form-control">
                                <option value="">--Search by Ward--</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb30">
                            <select name="state_constituency" id="state_constituency" class="form-control">
                                <option value="">--Search by State Constituency--</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb30">
                            <select name="polling_unit" id="polling_unit" class="form-control">
                                <option value="">--Search by Polling Unit--</option>
                            </select>
                        </div>
                        <div class="pull-right col-md-12">
                            <div class="col-md-1">
                                <input type="submit" name="search" id="search" class="btn btn-primary waves-effect" style="margin-top: 5px;" value="Filter">
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-primary waves-effect" style="background: #d9534f;margin-top: 5px;" onclick="resetPage()">Reset</button>
                            </div>
                        </div>
                    </form>

                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-hover" id="votersTable">
                            <thead>
                                <tr>
                                    <th>SL#</th>
                                    <th>Name</th>
                                    <th>Email Id</th>
                                    <th>Phone Number</th>
                                    <th>Age</th>
                                    <th>Employment Status</th>
                                    <th>Gender</th>
                                    <th>Address</th>
                                    <th>Zipcode</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated via Ajax -->
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#votersTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('managevotersdata.votersdata') }}",
                data: function(d) {
                    d.searchage = $('#searchage').val();
                    d.searchgender = $('#searchgender').val();
                    d.searchemp = $('#searchemp').val();
                    d.searchaddress = $('#searchaddress').val();
                    d.state = $('#state').val();
                    d.senatorial_state = $('#senatorial_state').val();
                    d.federal_constituency = $('#federal_constituency').val();
                    d.local_constituency = $('#local_constituency').val();
                    d.ward = $('#ward').val();
                    d.state_constituency = $('#state_constituency').val();
                    d.polling_unit = $('#polling_unit').val();
                }
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'fullname',
                    name: 'fullname'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'age',
                    name: 'age'
                },
                {
                    data: 'employ_status',
                    name: 'employ_status'
                },
                {
                    data: 'gender',
                    name: 'gender'
                },
                {
                    data: 'address',
                    name: 'address'
                },
                {
                    data: 'zipcode',
                    name: 'zipcode'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [9, 'desc']
            ],
            pageLength: 10,
            lengthChange: true,
        });

        $('#filterForm').on('submit', function(e) {
            e.preventDefault();
            table.draw();
        });
    });


    // Function to load dropdown data via AJAX
    function loadDropdownData(route, selector) {
        $.ajax({
            url: "{{ url('') }}/" + route,
            type: 'GET',
            success: function(response) {
                $(selector).html(response);
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    }

    // Function to populate dropdown options
    function populateDropdown(selector, data) {
        var dropdown = $(selector);
        dropdown.empty();
        dropdown.append($('<option>').val('').text('--Select--'));
        $.each(data, function(index, item) {
            dropdown.append($('<option>').val(item).text(item));
        });
    }

    $(document).ready(function() {
        // Initial load of dropdown data
        loadDropdownData('admin/manage-votersdata/state-list', '#state');
        loadDropdownData('admin/manage-votersdata/senatorial-list', '#senatorial_state');
        loadDropdownData('admin/manage-votersdata/federal-list', '#federal_constituency');
        loadDropdownData('admin/manage-votersdata/local-constituency-list', '#local_constituency');
        loadDropdownData('admin/manage-votersdata/ward-list', '#ward');
        loadDropdownData('admin/manage-votersdata/state-constituency-list', '#state_constituency');
        loadDropdownData('admin/manage-votersdata/polling-unit-list', '#polling_unit');
    });
</script>
<script>
    function getdistricts(state) {
        if (state) {
            $("#senatorial_state").html('');
            $("#federal_constituency").html('');
            $("#local_constituency").html('');
            $("#ward").html('');
            $("#state_constituency").html('');
            $("#polling_unit").html('');
            $.ajax({
                type: "POST",
                url: "{{ route('managevotersdata.getDistricts') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    state: state
                },
                success: function(data) {
                    var res = JSON.parse(data);
                    if (res.status1 == 2) {
                        $('#senatorial_state').html('<option value="">Select Senatorial State</option>');
                        alert('There is no Senatorial State.')
                    } else if (res.status1 == 1) {
                        $('#senatorial_state').html(res.html1);
                    }
                    if (res.status2 == 2) {
                        $('#federal_constituency').html(
                            '<option value="">Select Federal Constituency</option>');
                        alert('There is no Federal Constituency.')
                    } else if (res.status2 == 1) {
                        $('#federal_constituency').html(res.html2);
                    }
                    if (res.status3 == 2) {
                        $('#local_constituency').html(
                            '<option value="">Select Local constituency Area</option>');
                        alert('There is no Local constituency Area.')
                    } else if (res.status3 == 1) {
                        $('#local_constituency').html(res.html3);
                    }
                    if (res.status4 == 2) {
                        $('#ward').html('<option value="">Select Ward</option>');
                        alert('There is no Ward.')
                    } else if (res.status4 == 1) {
                        $('#ward').html(res.html4);
                    }
                    if (res.status5 == 2) {
                        $('#state_constituency').html(
                            '<option value="">Select State Constituency</option>');
                        alert('There is no State Constituency.')
                    } else if (res.status5 == 1) {
                        $('#state_constituency').html(res.html5);
                    }
                    if (res.status6 == 2) {
                        $('#polling_unit').html('<option value="">Select Polling Unit</option>');
                        alert('There is no Polling Unit.')
                    } else if (res.status6 == 1) {
                        $('#polling_unit').html(res.html6);
                    }
                }
            });
        }
    }


    function resetPage() {
        location.reload();
    }
</script>
@endsection