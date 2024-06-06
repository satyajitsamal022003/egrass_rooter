<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Site Content</title>
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
                           <li class="breadcrumb-item"><a href="index.html"><i class="icofont icofont-home"></i></a>
                           </li>
                           <li class="breadcrumb-item"><a href="#">Forms Components</a>
                           </li>
                           <li class="breadcrumb-item"><a href="form-elements-bootstrap.html">General Elements</a>
                           </li>
                        </ol>
                     </div>
                  </div>
               </div>
               <!-- Row end -->

              
               <div class="col-lg-8">
                     <div class="card">
                        <div class="card-header">
                           <h5 class="card-header-text">Add Site Content</h5>
                        </div>
                        <div class="card-block">
                           <form method="post" action="{{route('managesitecontent.store')}}" enctype="multipart/form-data">
                              @csrf
                              <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Title *</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="text" name="title" id="title" required>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="example-search-input" class="col-xs-2 col-form-label form-control-label">Reference Section *</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="text" name="reference" id="reference" required>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="example-url-input" class="col-xs-2 col-form-label form-control-label">Description *</label>
                                 <div class="col-sm-10">
                                 <textarea class="form-control" name="description" id="description" required></textarea>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label class="col-sm-2 col-form-label form-control-label">Image</label>
                                 <div class="col-sm-10">
                                 <input class="form-control" type="file" name="image" id="imageInput" accept="image/*" >
                                 <img id="imagePreview" class="img-fluid mt-2" style="height: 138px;width: 256px;" src="#" alt="Preview">
                              </div>
                              <div class="form-group row">
                                 <label for="example-number-input" class="col-xs-2 col-form-label form-control-label">Image Alt</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" name="imagalt" type="text"  id="imagalt">
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
