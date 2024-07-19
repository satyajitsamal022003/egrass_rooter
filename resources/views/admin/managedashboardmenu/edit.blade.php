<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update menu</title>

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
                     <li class="breadcrumb-item"><a href="{{route('managedashboardmenu.list')}}">Manage Dashborad Menu</a>
                     </li>
                     <li class="breadcrumb-item"><a>Edit Dashborad Menu</a>
                     </li>
                  </ol>
               </div>
            </div>
         </div>
         <!-- Row end -->
         <div class="col-lg-8">
            <div class="card">
               <div class="card-header">
                  <h5 class="card-header-text">Update menu</h5>
               </div>
               <div class="card-block">
                  <form method="post" action="{{route('managedashboardmenu.update',$editmanagemenu->id)}}" enctype="multipart/form-data">
                     @csrf
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Menu Name *</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="menu_name" id="menu_name" value="{{$editmanagemenu->menu_name}}" required>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-email-input" class="col-xs-2 col-form-label form-control-label">Menu Type</label>
                        <div class="col-sm-10">
                           <select class="form-control" name="menu_type" id="menu_type">
                              <option value="0" <?php if ($editmanagemenu['menu_type'] == 0) { ?> selected<?php } ?>>None</option>
                              <option value="3" <?php if ($editmanagemenu['menu_type'] == 3) { ?> selected<?php } ?>>Parent</option>
                              <option value="1" <?php if ($editmanagemenu['menu_type'] == 1) { ?> selected<?php } ?>>Add</option>
                              <option value="2" <?php if ($editmanagemenu['menu_type'] == 2) { ?> selected<?php } ?>>List</option>
                           </select>
                           </select>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Menu Link</label>
                        <div class="col-sm-10">
                           <input class="form-control col-md-7 col-xs-12" id="menu_link" name="menu_link" type="text" value="{{$editmanagemenu->menu_link}}">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-email-input" class="col-xs-2 col-form-label form-control-label">Select Parent</label>
                        <div class="col-sm-10">
                           <select class="form-control col-md-7 col-xs-12" name="select_parent" id="select_parent">
                              <option value="0" {{ $editmanagemenu->select_parent == 0 ? 'selected' : '' }}>None</option>
                              @foreach ($menuParents as $menuParent)
                              <option value="{{ $menuParent['id'] }}" {{ $editmanagemenu->select_parent == $menuParent['id'] ? 'selected' : '' }}>
                                 {{ $menuParent['name'] }}
                              </option>
                              @endforeach
                           </select>
                        </div>
                     </div>

                     @php
                     $menustatStyle = ($editmanagemenu['select_parent'] == 0 ? 'display:block' : 'display:none');
                     @endphp
                     <div class="form-group row" style="{{$menustatStyle}}">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Menu Class</label>
                        <div class="col-sm-10">
                           <input type="text" class="form-control col-md-7 col-xs-12" id="menu_class" name="menu_class" value="{{$editmanagemenu->menu_class}}" />
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Order No</label>
                        <div class="col-sm-10">
                           <input type="text" class="form-control col-md-7 col-xs-12" id="order_no" name="order_no" value="{{$editmanagemenu->order_no}}" />
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-email-input" class="col-xs-2 col-form-label form-control-label">Status</label>
                        <div class="col-sm-10">
                           <select class="form-control" name="status" id="status">
                              <option value="1" {{$editmanagemenu->status=='1'?'selected':''}}>Active</option>
                              <option value="0" {{$editmanagemenu->status=='0'?'selected':''}}>In Active</option>
                           </select>
                        </div>
                     </div>
                     <div class="form-group row">
                        <div class="col-sm-10 offset-sm-2">
                           <button type="submit" class="btn btn-primary">Update</button>
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