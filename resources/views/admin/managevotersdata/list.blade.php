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
                    <!-- <form id="filterForm" class="form-horizontal">
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
                    </form> -->

                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="datatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>SL#</th>
                                    <th>Name</th>
                                    <th>Email Id</th>
                                    <th>Phone Number</th>
                                    <th>Gender</th>
                                    <th>Address</th>
                                    <!-- <th>Actions</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @php($i=0)
                                @foreach ($contactdatalist as $contact)
                                <tr>
                                    <td>{{++$i}}</td>
                                    <td>{{$contact->name ?? 'N/A'}}</td>
                                    <td>{{$contact->email_id ?? 'N/A'}}</td>
                                    <td>{{$contact->phone_number ?? 'N/A'}}</td>
                                    <td>{{$contact->gender ?? 'N/A'}}</td>
                                    <td>{{$contact->address ?? 'N/A'}}</td>

                                    <!-- <td class="center">
                                        <a class="btn btn-info" href="{{route('managevotersdata.edit',$contact->id)}}" title="Edit"><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-danger" href="{{route('managevotersdata.destroy',$contact->id)}}" onclick="return confirm('Are you sure to delete!');" title="Delete"><i class="fa fa-remove"></i></a>
                                    </td> -->
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
@endsection