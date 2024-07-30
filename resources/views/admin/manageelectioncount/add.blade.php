<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add Election Votes</title>
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
                  <h4>Add Election Votes</h4>
                  <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
                     <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="icofont icofont-home"></i></a>
                     </li>
                     <li class="breadcrumb-item"><a href="{{route('manageelectionvoters.list')}}">Manage Election Votes</a>
                     </li>
                     <li class="breadcrumb-item"><a>Add Election Votes</a>
                     </li>
                  </ol>
               </div>
            </div>
         </div>
         <!-- Row end -->


         <div class="col-lg-8">
            <div class="card">
               <div class="card-header">
                  <h5 class="card-header-text">Add Election Votes</h5>
               </div>
               <div class="card-block">
                  <form method="post" action="{{route('manageelectionvoters.store')}}" enctype="multipart/form-data">
                     @csrf

                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">States *</label>
                        <div class="col-sm-10">
                           <select name="state" id="state" class="form-control" required>
                              <option value="">Select State</option>
                              @foreach (App\Models\State::all() as $statedata )
                              <option value="{{$statedata->id }}">{{$statedata->state }}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Party *</label>
                        <div class="col-sm-10">
                           <select name="party" id="party" class="form-control" required>
                              <option value="">Select Party</option>
                              @foreach (App\Models\Party::all() as $partydata )
                              <option value="{{$partydata->id }}">{{$partydata->party_name }}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Vote Value *</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="number" min="0" name="votevalue" id="votevalue" required>
                        </div>
                     </div>
                     <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                           <button type="submit" class="btn btn-primary">Add <i class="fa fa-plus"></i></button>
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