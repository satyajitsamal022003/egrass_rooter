<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Edit Campaign Organization</title>
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
                  <h4>Edit Campaign Organizations</h4>
                  <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
                     <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="icofont icofont-home"></i></a>
                     </li>
                     <li class="breadcrumb-item"><a href="{{route('managecampaignorgs.list')}}">Manage Campaign Organizations</a>
                     </li>
                     <li class="breadcrumb-item"><a>Edit Campaign Organizations</a>
                     </li>
                  </ol>
               </div>
            </div>
         </div>
         <!-- Row end -->


         <div class="col-lg-8">
            <div class="card">
               <div class="card-header">
                  <h5 class="card-header-text">Edit Campaign Organizations</h5>
               </div>
               <div class="card-block">
                  <form method="post" action="{{route('managecampaignorgs.update',$editcamporg->id)}}" enctype="multipart/form-data">
                     @csrf
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Name of campaign organization*</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="campaignorganization" id="campaignorganization" value="{{$editcamporg->name}}" required>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-search-input" class="col-xs-2 col-form-label form-control-label">Political office*</label>
                        <div class="col-sm-10">
                           <select class="form-control" id="political_office" name="political_office" required>
                              <option value="">Select Political Offices</option>
                              <option value="1" {{$editcamporg->political_office==1?'selected':''}}>President</option>
                              <option value="2" {{$editcamporg->political_office==2?'selected':''}}>Senate</option>
                              <option value="3" {{$editcamporg->political_office==3?'selected':''}}>House of Representative</option>
                              <option value="4" {{$editcamporg->political_office==4?'selected':''}}>Governor</option>
                              <option value="5" {{$editcamporg->political_office==5?'selected':''}}>House of Assembly</option>
                              <option value="6" {{$editcamporg->political_office==6?'selected':''}}>Local Government Chairman</option>
                              <!-- <option value="7">Councilor</option> -->
                           </select>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-search-input" class="col-xs-2 col-form-label form-control-label">Political party *</label>
                        <div class="col-sm-10">
                           <select name="politicalparty" id="politicalparty" class="form-control" required>
                              <option value="">Select</option>
                              @foreach (App\Models\Party::where('is_active',1)->get() as $partydata)
                              <option value="{{$partydata->id}}" {{$partydata->id==$editcamporg->political_party?'selected':''}}>{{$partydata->party_name}}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-url-input" class="col-xs-2 col-form-label form-control-label">Date registered*</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="date" name="dateregistered" id="dateregistered" min="<?php echo date('Y-m-d'); ?>" value="{{$editcamporg->date_register}}" required>
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

</html>
@endsection