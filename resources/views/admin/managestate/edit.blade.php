<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Edit State</title>
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
                     <li class="breadcrumb-item"><a href="{{route('managestate.list')}}">Manage State</a>
                     </li>
                     <li class="breadcrumb-item"><a>Edit State</a>
                     </li>
                  </ol>
               </div>
            </div>
         </div>
         <!-- Row end -->


         <div class="col-lg-8">
            <div class="card">
               <div class="card-header">
                  <h5 class="card-header-text">Edit State</h5>
               </div>
               <div class="card-block">
                  <form method="post" action="{{route('managestate.update',$editstate->id)}}">
                     @csrf
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">State Name*</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="state_name" value="{{$editstate->state}}" id="state_name" required>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Zone</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="zone" value="{{$editstate->zone}}" id="zone">
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