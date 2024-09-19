<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Edit Admin User</title>
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
                     <li class="breadcrumb-item"><a href="{{route('manageadmins.list')}}">Manage Admin</a>
                     </li>
                     <li class="breadcrumb-item"><a>Edit Admin</a>
                     </li>
                  </ol>
               </div>
            </div>
         </div>
         <!-- Row end -->


         <div class="col-lg-8">
            <div class="card">
               <div class="card-header">
                  <h5 class="card-header-text">Edit Admin User</h5>
               </div>
               <div class="card-block">
                  <form method="post" action="{{route('manageadmins.update',$adminedit->id)}}" enctype="multipart/form-data">
                     @csrf

                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Full Name *</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="fullname" id="fullname" value="{{$adminedit->name}}">
                           @error('fullname')
                           <span class="text-danger">{{ $message }}</span>
                           @enderror
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">User Name *</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="username" id="username" value="{{$adminedit->uname}}">
                           @error('username')
                           <span class="text-danger">{{ $message }}</span>
                           @enderror
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Phone No *</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="phone_no" id="phone_no" value="{{$adminedit->contact_no}}">
                           @error('phone_no')
                           <span class="text-danger">{{ $message }}</span>
                           @enderror
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">E-Mail ID *</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="email" name="email" id="email" value="{{$adminedit->email}}">
                           @error('email')
                           <span class="text-danger">{{ $message }}</span>
                           @enderror
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Password *</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="password" name="password" id="password">
                           @error('password')
                           <span class="text-danger">{{ $message }}</span>
                           @enderror
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">User Type</label>
                        <div class="col-sm-10">
                           <select name="usertype" id="usertype" class="form-control">
                              <option value="">Select User Type</option>
                              <option value="1" {{$adminedit->user_type==1?'selected':''}}>Admin</option>
                              <option value="0" {{$adminedit->user_type==0?'selected':''}}>Staff</option>
                           </select>
                           @error('usertype')
                           <span class="text-danger">{{ $message }}</span>
                           @enderror
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-email-input" class="col-xs-2 col-form-label form-control-label">Status</label>
                        <div class="col-sm-10">
                           <select class="form-control" name="status" id="status">
                              <option value="1" {{$adminedit->is_active=='1'?'selected':''}}>Publish</option>
                              <option value="0" {{$adminedit->is_active=='0'?'selected':''}}>UnPublish</option>
                           </select>
                        </div>
                     </div>
                     <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                           <button type="submit" class="btn btn-success">Update admin</button>
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