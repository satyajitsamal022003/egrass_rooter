<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Edit Service</title>
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
                  <h4>Edit Service</h4>
                  <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
                     <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="icofont icofont-home"></i></a>
                     </li>
                     <li class="breadcrumb-item"><a href="{{route('manageservices.list')}}">Manage Service</a>
                     </li>
                     <li class="breadcrumb-item"><a>Edit Service</a>
                     </li>
                  </ol>
               </div>
            </div>
         </div>
         <!-- Row end -->


         <div class="col-lg-8">
            <div class="card">
               <div class="card-header">
                  <h5 class="card-header-text">Edit Service</h5>
               </div>
               <div class="card-block">
                  <form method="post" action="{{route('manageservices.update',$editservices->id)}}" enctype="multipart/form-data">
                     @csrf

                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Title*</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="title" id="title" value="{{$editservices->title}}" required>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Content *</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="content" id="content" value="{{$editservices->content}}" required>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Service Class *</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="serviceclass" id="serviceclass" value="{{$editservices->icon}}" required>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-email-input" class="col-xs-2 col-form-label form-control-label">Status</label>
                        <div class="col-sm-10">
                           <select class="form-control" name="status" id="status">
                              <option value="1" {{$editservices->is_active=='1'?'selected':''}}>Publish</option>
                              <option value="0" {{$editservices->is_active=='0'?'selected':''}}>UnPublish</option>
                           </select>
                        </div>
                     </div>
                     <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                           <button type="submit" class="btn btn-primary">Update Service</button>
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