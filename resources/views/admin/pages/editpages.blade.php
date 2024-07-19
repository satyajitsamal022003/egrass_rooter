<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Edit Page</title>
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
                     <li class="breadcrumb-item"><a href="{{route('pages.index')}}">Manage Pages</a>
                     </li>
                     <li class="breadcrumb-item"><a>Edit Page</a>
                     </li>
                  </ol>
               </div>
            </div>
         </div>
         <!-- Row end -->


         <div class="col-lg-8">
            <div class="card">
               <div class="card-header">
                  <h5 class="card-header-text">Edit Page/Content</h5>
               </div>
               <div class="card-block">
                  <form method="post" action="{{route('pages.update',$editpages->id)}}" enctype="multipart/form-data">
                     @csrf
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Title *</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="title" id="title" value="{{$editpages->page_name }}" required>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-search-input" class="col-xs-2 col-form-label form-control-label">Slug Name *</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="slug_name" id="slug" readonly value="{{$editpages->slug  }}">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-email-input" class="col-xs-2 col-form-label form-control-label">Page Name</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="page_name" id="page_name" value="{{$editpages->content_title }}">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-url-input" class="col-xs-2 col-form-label form-control-label">Description</label>
                        <div class="col-sm-10">
                           <textarea class="form-control" name="description" id="description">{{$editpages->description }} </textarea>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-tel-input" class="col-xs-2 col-form-label form-control-label">Short Description</label>
                        <div class="col-sm-10">
                           <textarea class="form-control" name="shortdesc" id="shortdesc">{{$editpages->short_description }} </textarea>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-password-input" class="col-xs-2 col-form-label form-control-label">Bottom Description</label>
                        <div class="col-sm-10">
                           <textarea class="form-control" name="bottom_desc" id="bottom_desc">{{$editpages->btm_description }} </textarea>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label class="col-sm-2 col-form-label form-control-label">Image</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="file" name="image" id="imageInput" accept="image/*">
                           <img id="imagePreview" class="img-fluid mt-2" style="height: 138px;width: 256px;" src="{{ asset('images/pages/' . $editpages->image) }}" alt="Preview">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label class="col-sm-2 col-form-label form-control-label"> Banner Image</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="file" name="iconbanner_image" id="bannerImageInput" accept="image/*">
                           <img id="bannerImagePreview" class="img-fluid mt-2" style="height: 138px;width: 256px;" src="{{ asset('images/pages/' . $editpages->banner_image	) }}" alt="Preview">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-number-input" class="col-xs-2 col-form-label form-control-label">Banner Text</label>
                        <div class="col-sm-10">
                           <input class="form-control" name="banner_text" type="text" id="banner_text" value="{{$editpages->page_banner_text }}">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-number-input" class="col-xs-2 col-form-label form-control-label">Meta Title</label>
                        <div class="col-sm-10">
                           <input class="form-control" name="metatitle" type="text" value="{{$editpages->meta_title }}" id="metatitle">
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-number-input" class="col-xs-2 col-form-label form-control-label">Meta Key</label>
                        <div class="col-sm-10">
                           <textarea class="form-control" name="meta_key" id="meta_key">{{$editpages->meta_key }}
                           </textarea>
                        </div>
                     </div>

                     <div class="form-group row">
                        <label for="example-number-input" class="col-xs-2 col-form-label form-control-label">Meta Description</label>
                        <div class="col-sm-10">
                           <textarea class="form-control" name="meta_description" id="meta_description">{{$editpages->meta_desc }}
                           </textarea>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-email-input" class="col-xs-2 col-form-label form-control-label">Status</label>
                        <div class="col-sm-10">
                           <select class="form-control" name="status" id="status">
                              <option value="1" {{$editpages->is_active=='1'?'selected':''}}>Publish</option>
                              <option value="0" {{$editpages->is_active=='2'?'selected':''}}>UnPublish</option>
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
      // CKEDITOR.replace('description', {
      //    allowedContent: true,
      //    extraPlugins: 'colorbutton',
      //    enterMode: CKEDITOR.ENTER_BR
      // });
      // CKEDITOR.replace('bottom_desc', {
      //    allowedContent: true,
      //    extraPlugins: 'colorbutton',
      //    enterMode: CKEDITOR.ENTER_BR
      // });
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