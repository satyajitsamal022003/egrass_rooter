<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add Blog</title>
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
                     <li class="breadcrumb-item"><a href="{{route('manageblog.list')}}">Manage Blogs</a>
                     </li>
                     <li class="breadcrumb-item"><a>Add Blog</a>
                     </li>
                  </ol>
               </div>
            </div>
         </div>
         <!-- Row end -->


         <div class="col-lg-8">
            <div class="card">
               <div class="card-header">
                  <h5 class="card-header-text">Add Blog</h5>
               </div>
               <div class="card-block">
                  <form method="post" action="{{route('manageblog.store')}}" enctype="multipart/form-data">
                     @csrf
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Category *</label>
                        <div class="col-sm-10">
                           <select name="category" id="category" class="form-control" required>
                              <option value="">--Select--</option>
                              @foreach (App\Models\BlogCategory :: all() as $blogcategory)
                              <option value="{{$blogcategory->cat_id}}">{{$blogcategory->cat_title}}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Tag</label>
                        <div class="col-sm-10">
                           <select name="tag" id="tag" class="form-control">
                              <option value="">--Select--</option>
                              @foreach (App\Models\BlogTag :: all() as $blogtag)
                              <option value="{{$blogtag->tag_id}}">{{$blogtag->tag_title}}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Title *</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="title" id="title" required>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-search-input" class="col-xs-2 col-form-label form-control-label">Slug Name *</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="slug" id="slug" readonly>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-url-input" class="col-xs-2 col-form-label form-control-label">Description</label>
                        <div class="col-sm-10">
                           <textarea class="form-control" name="description" id="description"></textarea>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label class="col-sm-2 col-form-label form-control-label">Image</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="file" name="image" id="imageInput" accept="image/*" required>
                           <img id="imagePreview" class="img-fluid mt-2" style="height: 138px;width: 256px;" src="#" alt="Preview">
                        </div>
                        <div class="form-group row">
                           <label for="example-number-input" class="col-xs-2 col-form-label form-control-label">Meta Title</label>
                           <div class="col-sm-10">
                              <input class="form-control" name="metatitle" type="text" id="metatitle">
                           </div>
                        </div>
                        <div class="form-group row">
                           <label for="example-number-input" class="col-xs-2 col-form-label form-control-label">Meta Key</label>
                           <div class="col-sm-10">
                              <textarea class="form-control" name="meta_key" id="meta_key">
                                    </textarea>
                           </div>
                        </div>

                        <div class="form-group row">
                           <label for="example-number-input" class="col-xs-2 col-form-label form-control-label">Meta Description</label>
                           <div class="col-sm-10">
                              <textarea class="form-control" name="meta_description" id="meta_description">
                                    </textarea>
                           </div>
                        </div>
                        <div class="form-group row">
                           <label for="example-email-input" class="col-xs-2 col-form-label form-control-label">Status</label>
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

   <!-- Include CKEditor script -->
   <script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script>
   <script>
      // Replace the <textarea> with CKEditor
      CKEDITOR.replace('description');
      CKEDITOR.replace('bottom_desc');
   </script>

   <script>
      // Function to generate a slug from a given string
      function generateSlug(str) {
         return str.toLowerCase().replace(/\s+/g, '-').replace(/[^\w-]+/g, '');
      }

      // Function to update the slug as the user types in the title input
      document.getElementById('title').addEventListener('input', function() {
         const title = this.value;
         const slug = generateSlug(title);
         document.getElementById('slug').value = slug;
      });
   </script>

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