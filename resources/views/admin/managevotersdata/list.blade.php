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
        <h4>Manage Contacts Data</h4>
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
          <form action="{{ url('votes/votersdatalist') }}" method="GET" class="form-horizontal" onsubmit="return filterFormValidate();">
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
                @if(!empty($getAllemp) && count($getAllemp) > 0)
                @foreach($getAllemp as $getemp)
                <option value="{{ $getemp->employ_status }}" {{ request('searchemp') == $getemp->employ_status ? 'selected' : '' }}>{{ stripslashes($getemp->employ_status) }}</option>
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
              <select id="state" name="state" class="form-control" onchange="getdistricts(this.value);">
                <option value="">--Search by State--</option>
                @foreach($stateList as $stateListDet)
                <option value="{{ utf8_encode($stateListDet->state) }}">{{ utf8_encode($stateListDet->state) }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3 mb30">
              <select name="senatorial_state" id="senatorial_state" class="form-control">
                <option value="">--Search by Senatorial State--</option>
                @foreach($senatorialList as $senatorialDet)
                <option value="{{ utf8_encode($senatorialDet->sena_district) }}">{{ utf8_encode($senatorialDet->sena_district) }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3 mb30">
              <select name="federal_constituency" id="federal_constituency" class="form-control">
                <option value="">--Search by Federal Constituency--</option>
                @foreach($federalList as $federalDet)
                <option value="{{ utf8_encode($federalDet->federal_name) }}">{{ utf8_encode($federalDet->federal_name) }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3 mb30">
              <select name="local_constituency" id="local_constituency" class="form-control">
                <option value="">--Search by LGA--</option>
                @foreach($localConList as $localConListDet)
                <option value="{{ utf8_encode($localConListDet->lga) }}">{{ utf8_encode($localConListDet->lga) }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3 mb30">
              <select name="ward" id="ward" class="form-control">
                <option value="">--Search by Ward--</option>
                @foreach($wardList as $wardDet)
                <option value="{{ utf8_encode($wardDet->ward_details) }}">{{ utf8_encode($wardDet->ward_details) }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3 mb30">
              <select name="state_constituency" id="state_constituency" class="form-control">
                <option value="">--Search by State Constituency--</option>
                @foreach($staList as $staDet)
                <option value="{{ utf8_encode($staDet->state_constituency) }}">{{ utf8_encode($staDet->state_constituency) }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3 mb30">
              <select name="polling_unit" id="polling_unit" class="form-control">
                <option value="">--Search by Polling Unit--</option>
                @foreach($pollingList as $pollingDet)
                <option value="{{ utf8_encode($pollingDet->polling_name) }}">{{ utf8_encode($pollingDet->polling_name) }}</option>
                @endforeach
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
            <table id="datatable" class="table table-bordered table-striped">
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
                @php($i=0)
                @foreach ($votersData as $votersDetails)
                <tr>
                  <td>{{++$i}}</td>
                  <td>{{$votersDetails->name}}</td>
                  <td>{{ $votersDetails->email }}</td>
                  <td>{{ $votersDetails->phone_number }}</td>
                  <td>{{ $votersDetails->age }}</td>
                  <td>{{ $votersDetails->employ_status }}</td>
                  <td>{{ $votersDetails->gender }}</td>
                  <td>{{ $votersDetails->address }}</td>
                  <td>{{ $votersDetails->zipcode }}</td>
                  <td>{{date('d-m-Y', strtotime($votersDetails->created)) }}</td>

                  <td class="center">
                    <a class="btn btn-info" href="{{route('managevotersdata.edit',$votersDetails->id)}}" title="Edit"><i class="fa fa-edit"></i></a>
                    <a class="btn btn-danger" href="{{route('managevotersdata.destroy',$votersDetails->id)}}" onclick="return confirm('Are you sure to delete!');" title="Delete"><i class="fa fa-remove"></i></a>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
  function resetPage() {
    location.reload();
  }
</script>
@endsection