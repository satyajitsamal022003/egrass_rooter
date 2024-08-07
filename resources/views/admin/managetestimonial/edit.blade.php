<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Edit Client Testimonials</title>

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
                     <li class="breadcrumb-item"><a href="{{route('managetestimonial.list')}}">Manage Testimonials</a>
                     </li>
                     <li class="breadcrumb-item"><a>Edit Testimonials</a>
                     </li>
                  </ol>
               </div>
            </div>
         </div>
         <!-- Row end -->
         <div class="col-lg-8">
            <div class="card">
               <div class="card-header">
                  <h5 class="card-header-text">Edit Testimonials</h5>
               </div>
               <div class="card-block">
                  <form method="post" action="{{route('managetestimonial.update',$edittestimonial->id)}}" enctype="multipart/form-data">
                     @csrf
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Client Name *</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="clientname" value="{{$edittestimonial->client_name}}" id="clientname" required>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Client Image</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="file" name="clientimage" id="imageInput" accept="image/*">
                           <img id="imagePreview" class="img-fluid mt-2" style="height: 151px;width: 153px;border-radius: 79px;" src="{{ asset('images/testimonials/' . $edittestimonial->client_image) }}" alt="Preview">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Client Designation*</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="clientdesig" id="clientdesig" value="{{$edittestimonial->position}}">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Message description*</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="msg_desc" id="msg_desc" value="{{$edittestimonial->description}}">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-email-input" class="col-xs-2 col-form-label form-control-label">Status</label>
                        <div class="col-sm-10">
                           <select class="form-control" name="status" id="status">
                              <option value="1" {{$edittestimonial->is_active=='1'?'selected':''}}>Publish</option>
                              <option value="0" {{$edittestimonial->is_active=='0'?'selected':''}}>UnPublish</option>
                           </select>
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
   <script>
      document.addEventListener('DOMContentLoaded', function() {
         const imageInput = document.getElementById('imageInput');
         const imagePreview = document.getElementById('imagePreview');
         imageInput.addEventListener('change', function() {
            displayImagePreview(this, imagePreview);
         });

         function displayImagePreview(input, preview) {
            if (input.files && input.files[0]) {
               const reader = new FileReader();
               reader.onload = function(e) {
                  preview.src = e.target.result;
               };
               reader.readAsDataURL(input.files[0]);
            } else {
               preview.src = '#';
            }
         }
      });
   </script>

</html>
@endsection