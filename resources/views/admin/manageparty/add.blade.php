<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add Party</title>
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
                     <li class="breadcrumb-item"><a href="{{route('manageparty.list')}}">Manage Party</a>
                     </li>
                     <li class="breadcrumb-item"><a>Add Party</a>
                     </li>
                  </ol>
               </div>
            </div>
         </div>
         <!-- Row end -->


         <div class="col-lg-8">
            <div class="card">
               <div class="card-header">
                  <h5 class="card-header-text">Add Party</h5>
               </div>
               <div class="card-block">
                  <form action="{{route('manageparty.store')}}" method="post" enctype="multipart/form-data">
                     @csrf
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-3 col-form-label form-control-label">Party Name*</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="party_name" id="party_name" required>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-3 col-form-label form-control-label">Party chairman name</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="chairman_name" id="chairman_name" required>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-3 col-form-label form-control-label">Color *</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="color" id="color" required>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-3 col-form-label form-control-label">Party Image</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="file" name="party_image" id="imageInput" accept="image/*">
                           <img id="imagePreview" class="img-fluid mt-2" style="height: 138px;width: 256px;" src="#" alt="Preview">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-3 col-form-label form-control-label">Candidate Image</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="file" name="candidate_img" id="bannerImageInput" accept="image/*">
                           <img id="bannerImagePreview" class="img-fluid mt-2" style="height: 138px;width: 256px;" src="#" alt="Preview">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-email-input" class="col-xs-3 col-form-label form-control-label">Status</label>
                        <div class="col-sm-10">
                           <select class="form-control" name="status" id="status">
                              <option value="1">Publish</option>
                              <option value="0" selected>UnPublish</option>
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

   <!-- image upload -->
   <script>
      document.addEventListener('DOMContentLoaded', function() {
         const imageInput = document.getElementById('imageInput');
         const imagePreview = document.getElementById('imagePreview');
         imageInput.addEventListener('change', function() {
            displayImagePreview(this, imagePreview);
         });

         const bannerImageInput = document.getElementById('bannerImageInput');
         const bannerImagePreview = document.getElementById('bannerImagePreview');
         bannerImageInput.addEventListener('change', function() {
            displayImagePreview(this, bannerImagePreview);
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