<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
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

              
               <div class="col-lg-8">
                     <div class="card">
                        <div class="card-header">
                           <h5 class="card-header-text">Add New User</h5>
                        </div>
                        <div class="card-block">
                           <form action="{{route('manageusers.store')}}" method="post">
                              @csrf
                              <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">First Name *</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="text" name="firstname" id="firstname" value="{{old('firstname')}}">
                                        @error('firstname')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                 </div>
                                 </div>
                                 <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">last Name *</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="text" name="lastname" id="lastname" value="{{old('lastname')}}">
                                        @error('lastname')
                                                <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                 </div>
                                 </div>
                                 <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Email ID *</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="email" name="email_id" id="email_id" value="{{old('email_id')}}">
                                        @error('email_id')
                                                <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                 </div>
                                 </div>
                                 <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Phone Number</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="text" name="phnno" id="phnno" value="{{old('phnno')}}" onkeypress="return isNumberKey(event)" >
                                 </div>
                                 </div>
                                 <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Password *</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="text" name="password" id="password" >
                                        @error('password')
                                                <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                 </div>
                                 </div>
                                 <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">DOB*</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="date" name="dob" id="dob" max="{{date('Y-m-d')}}" value="{{old('dob')}}">
                                        @error('dob')
                                                <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                 </div>
                                 </div>
                                 <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Address*</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="text" name="address" id="address" value="{{old('address')}}">
                                        @error('address')
                                                <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                 </div>
                                 </div>
                                 <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Gender</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="text" name="gender" id="gender" value="{{old('gender')}}">
                                 </div>
                                 </div>
                                 <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Level of Education*</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="text" name="levelofeduc" id="levelofeduc" value="{{old('levelofeduc')}}">
                                        @error('levelofeduc')
                                                <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                 </div>
                                 </div>
                                 <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Origin town</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="text" name="origintown" id="origintown" value="{{old('origintown')}}">
                                        @error('origintown')
                                                <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                 </div>
                                 </div>
                                 <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Campaign Name*</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="text" name="campaign_name" id="campaign_name" value="{{old('campaign_name')}}" onkeyup="return slugName(0);" onblur="return slugName(0);">
                                        @error('campaign_name')
                                                <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                        <span id="showmsg"></span>
                                 </div>
                                 </div>
                                 <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Campaign URL*</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="text" name="campaign_url" id="campaign_url" value="{{old('campaign_url')}}" onblur="return clslugName(0);">
                                        @error('campaign_url')
                                                <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                 </div>
                                 </div>
                                 <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Campaign Type*</label>
                                 <div class="col-sm-10">
                                 <select name="campaign_type" id="campaign_type" onchange="getval();" class="form-control">
                                    <option value="">Select Campaign Type</option>
                                    <option value="1">President</option>
                                    <option value="2">Senate</option>
                                    <option value="3">House of Representative</option>
                                    <option value="4">Governor</option>
                                    <option value="5">House of Assembly</option>
                                    <option value="6">Local Government Chairman</option>
                                  </select>
                                        @error('campaign_type')
                                                    <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                 </div>
                                 </div>
                                 <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">User Type*</label>
                                 <div class="col-sm-10">
                                 <select name="user_type" id="user_type" class="form-control">
                                    <option value="">Select User Type</option>
                                    <option value="Campaign Admin">Campaign Admin</option>
                                    <option value="Campaign Staff">Campaign Staff</option>
                                    <option value="Party Members">Party Members</option>
                                    <option value="Grassrooters">Grassrooters</option>
                                    <option value="Volunteers">Volunteers</option>
                                    <option value="Donors">Donors</option>
                                    <option value="Potential Voters/grassroots">Potential Voters/grassroots</option>
                                    <option value="NGOs/Pressure Groups">NGOs/Pressure Groups</option>
                                    <option value="Polling Agents">Polling Agents</option>
                                    <option value="Delegates">Delegates</option>
                                  </select>
                                        @error('user_type')
                                                    <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                 </div>
                                 </div>
                                 <div class="form-group row" id="statemainid">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">State*</label>
                                 <div class="col-sm-10">
                                 <select name="state_id" id="state_id" class="form-control"   onchange="stateid(this.value)">
                                        <option value="">Select State</option>
                                        @foreach (App\Models\State::all() as $statedata )
                                           <option value="{{$statedata->id }}">{{$statedata->state }}</option>
                                        @endforeach
                                 </select>
                                        @error('state_id')
                                                    <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                 </div>
                                 </div>
                                 <div class="form-group row" id="senomainid">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Senatorial State*</label>
                                 <div class="col-sm-10">
                                 <select name="senotorial_state" id="senotorial_state" class="form-control">
                                   <!-- values will be appended from state selection -->
                                  </select>
                                    @error('senotorial_state')
                                                <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                 </div>
                                 </div>
                                 <div class="form-group row" id="federalmainid">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Federal Constituency*</label>
                                 <div class="col-sm-10">
                                 <select name="federal_const" id="federal_const" class="form-control">
                                    <option value="">--Select--</option>
                                    @foreach ($federalList as $federalDet)
                                        <option value="{{$federalDet->id}}">{{$federalDet->federal_name}}</option>
                                    @endforeach
                                  </select>
                                        @error('federal_const')
                                                    <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                 </div>
                                 </div>
                                 <div class="form-group row" id="lcamainid">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Local constituency Area*</label>
                                 <div class="col-sm-10">
                                 <select name="lca" id="lca" class="form-control">
                                 <option value="">--Select--</option>
                                    @foreach ($localConList as $loc)
                                    <option value="{{$loc->id}}">{{$loc->lga}}</option>
                                    @endforeach
                                 </select>
                                    @error('lca')
                                                <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                 </div>
                                 </div>
                                
                                 <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">What is the name your political party*</label>
                                 <div class="col-sm-10">
                                  <select name="political_party" id="political_party" class="form-control">
                                    <option value="">--Select Party--</option>
                                    @foreach ($getAllParty as $party)
                                    <option value="{{$party->id}}">{{$party->party_name}}</option>
                                    @endforeach
                                  </select>
                                    @error('political_party')
                                                <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                 </div>
                                 </div>
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

        <!-- Show hide as per campaigntype -->
        <script>
            function getval() {
          var x = $("#campaign_type").val();
          if (x == 1) {
            $("#statemainid").hide();
            $("#senomainid").hide();
            $("#federalmainid").hide();
            $("#lcamainid").hide();
          } else if (x == 2) {
            $("#statemainid").show();
            $("#senomainid").show();
            $("#federalmainid").hide();
            $("#lcamainid").hide();
          } else if (x == 3) {
            $("#statemainid").show();
            $("#senomainid").hide();
            $("#federalmainid").show();
            $("#lcamainid").hide();
          } else if (x == 4) {
            $("#statemainid").show();
            $("#senomainid").hide();
            $("#federalmainid").hide();
            $("#lcamainid").hide();
          } else if (x == 5) {
            $("#statemainid").show();
            $("#senomainid").hide();
            $("#federalmainid").hide();
            $("#lcamainid").hide();
          } else if (x == 6) {
            $("#statemainid").show();
            $("#senomainid").hide();
            $("#federalmainid").hide();
            $("#lcamainid").show();
          } else {
            $("#statemainid").show();
            $("#senomainid").show();
            $("#federalmainid").show();
            $("#lcamainid").show();
          }


        }
        </script>
<!-- get senatorial as per state selection -->
<script>
  function stateid(id){  
  
    $.ajax({
         url:"{{route('manageusers.getsenstates')}}",
         type:"POST",
         data: {
        "_token": "{{ csrf_token() }}",
        id: id
        },
        success :function(response){
          if (response.code == 200) {
            var len=response['status'].length;  
    
          var option='<option value="" selected disabled>--Select Senatorial State--</option>';

            if(len == 0){
              
               option+= '<option  selected disabled>--No Senatorial State Found--</option>'; 
              
            }
            for(var i=0; i<len; i++){

              var id = response['status'][i].id;
              var name = response['status'][i].sena_district;

               option+= "<option value='"+id+"'>"+name+"</option>"; 
               }
               $("#senotorial_state").html(" ");
              $("#senotorial_state").append(option); 

              
                        
          }
          
        }
    })
  }
</script>

<!-- phone number validation -->
<script>
    function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode != 45 && charCode != 32 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    var phonelength = $("#phnno").val().length;
    if (phonelength > 12) {
        return false;
    }
    return true;
}
</script>

<!-- check slug uniqueness -->
<script>
    function clslugName(todo) {
    var title = $("#campaign_url").val();
    var blankTest = /\S/;
    if (blankTest.test(title)) {
        $.ajax({
            type: 'POST',
            url: '{{ route("manageusers.checkslug") }}', // Assuming you have a route named 'checkSlug'
            data: {
                title: title,
                editid: todo
            },
            success: function(data) {
                if (data == 2) {
                    $("#slugerror").html('<font color="red">Slug Already exists</font>');
                    $("#campaign_url").val('');
                    $("#campaign_url").focus();
                } else {
                    var data1 = $.trim(data);
                    $("#campaign_url").val(data1);
                    $("#slugerror").html('');
                }
            }
        });
    }
}

function slugName(todo) {
    var title = $("#campaign_name").val();
    var blankTest = /\S/;
    if (blankTest.test(title)) {
        var website = ".egrassrooter.com"; // You can set your website URL dynamically or manually
        $("#campaign_url").val(title + website);
        $("#slugerror").html('');
    }
}

</script>


</html>
@endsection
