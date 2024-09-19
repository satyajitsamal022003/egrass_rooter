<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add Polling Unit</title>
</head>
@extends('layouts.admin.layout')
@section('section')

<body>
   <div class="container-fluid">
      <!-- Main content starts -->
      <div>
         <!-- Row Starts -->
         <div class="row">
            <div class="col-sm-12 p-0">
               <div class="main-header">
                  <h4>General Elements</h4>
                  <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
                     <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="icofont icofont-home"></i></a>
                     </li>
                     <li class="breadcrumb-item"><a href="{{route('managepollings.list')}}">Manage Polling Unit</a>
                     </li>
                     <li class="breadcrumb-item"><a>Add Polling Unit</a>
                     </li>
                  </ol>
               </div>
            </div>
         </div>
         <!-- Row end -->


         <div class="col-lg-8">
            <div class="card">
               <div class="card-header">
                  <h5 class="card-header-text">Add Polling Unit</h5>
               </div>
               <div class="card-block">
                  <form action="{{route('managepollings.store')}}" method="post" enctype="multipart/form-data" onsubmit="return pollingFormValidate(0);">
                     @csrf
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">State</label>
                        <div class="col-sm-10">
                           <select name="state_id" id="state_id" class="form-control" onchange="stateid(this.value)">
                              <option value="">Select State</option>
                              @foreach (App\Models\State::all() as $statedata )
                              <option value="{{$statedata->id }}">{{$statedata->state }}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Lga *</label>
                        <div class="col-sm-10">
                           <select name="lga" id="lga" class="form-control" onchange="warddata(this.value)" required>
                              <option value="">Select Lga</option>

                           </select>
                        </div>
                     </div>
                     <!-- </div> -->
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Political Zone *</label>
                        <div class="col-sm-10">
                           <select name="political_zone" id="political_zone" data-rel="chosen" class="form-control">
                              <option value="">Select Polling Zone</option>
                              <option value="NORTH WEST">NORTH WEST</option>
                              <option value="SOUTH WEST">SOUTH WEST</option>
                              <option value="NORTH CENTRAL">NORTH CENTRAL</option>
                              <option value="SOUTH SOUTH">SOUTH SOUTH</option>
                              <option value="NORTH EAST">NORTH EAST</option>
                              <option value="SOUTH EAST">SOUTH EAST</option>
                           </select>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Ward *</label>
                        <div class="col-sm-10">
                           <select name="ward_details" id="ward_details" class="form-control" required>
                              <option value="">Select Ward</option>
                           </select>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-3 col-form-label form-control-label">Polling Name *</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="polling_name" id="polling_name">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-3 col-form-label form-control-label">Polling Capacity *</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="number" name="polling_capacity" id="polling_capacity" min="0">
                        </div>
                     </div>
                     <!-- <div class="form-group row">
                        <label for="example-text-input" class="col-xs-3 col-form-label form-control-label">Delims *</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="delims" id="delims">
                        </div>
                     </div> -->
                     <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                           <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>

      </div>

      <!-- Main content ends -->
   </div>

   <!-- Validation -->
   <script type="text/javascript">
      function pollingFormValidate() {
         var state_id = $("#state_id").val();
         var lga = $("#lga").val();
         var ward_details = $("#ward_details").val();
         var polling_name = $("#polling_name").val();
         var polling_capacity = $("#polling_capacity").val();
         var blankTest = /\S/;
         if (!blankTest.test(state_id)) {
            $("#state_id").css("background-color", "rgb(247, 211, 216)");
            $("#state_id").focus();
            return false;
         } else {
            $("#state_id").css("background-color", "");
         }
         if (!blankTest.test(lga)) {
            $("#lga").css("background-color", "rgb(247, 211, 216)");
            $("#lga").focus();
            return false;
         } else {
            $("#lga").css("background-color", "");
         }
         if (!blankTest.test(ward_details)) {
            $("#ward_details").css("background-color", "rgb(247, 211, 216)");
            $("#ward_details").focus();
            return false;
         } else {
            $("#ward_details").css("background-color", "");
         }
         if (!blankTest.test(polling_name)) {
            $("#polling_name").css("background-color", "rgb(247, 211, 216)");
            $("#polling_name").focus();
            return false;
         } else {
            $("#polling_name").css("background-color", "");
         }
         if (!blankTest.test(polling_capacity)) {
            $("#polling_capacity").css("background-color", "rgb(247, 211, 216)");
            $("#polling_capacity").focus();
            return false;
         } else {
            $("#polling_capacity").css("background-color", "");
         }


      }
   </script>
   <!-- get lgas -->

   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

   <script>
      function stateid(id) {

         $.ajax({
            url: "{{route('managepollings.getLgas')}}",
            type: "POST",
            data: {
               "_token": "{{ csrf_token() }}",
               id: id
            },
            success: function(response) {
               if (response.code == 200) {
                  var len = response['status'].length;

                  var option = '<option value="" selected disabled>--Select Lga--</option>';

                  if (len == 0) {

                     option += '<option  selected disabled>--No Lga Found--</option>';

                  }
                  for (var i = 0; i < len; i++) {

                     var id = response['status'][i].id;
                     var name = response['status'][i].lga;

                     option += "<option value='" + id + "'>" + name + "</option>";
                  }
                  $("#lga").html(" ");
                  $("#lga").append(option);



               }

            }
         })
      }
   </script>

   <script>
      function warddata(id) {

         $.ajax({
            url: "{{route('managepollings.getwards')}}",
            type: "POST",
            data: {
               "_token": "{{ csrf_token() }}",
               id: id
            },
            success: function(response) {
               if (response.code == 300) {
                  var len = response['status'].length;

                  var option = '<option value="" selected disabled>--Select Ward--</option>';

                  if (len == 0) {

                     option += '<option  selected disabled>--No Ward Found--</option>';

                  }
                  for (var i = 0; i < len; i++) {

                     var id = response['status'][i].id;
                     var name = response['status'][i].ward_details;

                     option += "<option value='" + id + "'>" + name + "</option>";
                  }
                  $("#ward_details").html(" ");
                  $("#ward_details").append(option);



               }

            }
         })
      }
   </script>



</html>
@endsection