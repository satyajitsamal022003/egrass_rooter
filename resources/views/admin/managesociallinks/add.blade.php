<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add Social Link</title>
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
                     <li class="breadcrumb-item"><a href="{{route('managesociallinks.list')}}">Manage Social Link</a>
                     </li>
                     <li class="breadcrumb-item"><a>Add Social Link</a>
                     </li>
                  </ol>
               </div>
            </div>
         </div>
         <!-- Row end -->


         <div class="col-lg-8">
            <div class="card">
               <div class="card-header">
                  <h5 class="card-header-text">Add Social Link</h5>
               </div>
               <div class="card-block">
                  <form action="{{route('managesociallinks.store')}}" method="post" enctype="multipart/form-data">
                     @csrf
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-3 col-form-label form-control-label">Social Name *</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="social_name" id="social_name" required>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-3 col-form-label form-control-label">Social Link *</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="url" name="social_link" id="social_link" placeholder="  e.g ( http://)" required><br>
                           <div class="invalid-feedback">Please enter a valid URL.</div>
                        </div>
                     </div>
                     <div class="form-group row">
                        <label for="example-text-input" class="col-xs-3 col-form-label form-control-label">Social Class *</label>
                        <div class="col-sm-10">
                           <input class="form-control" type="text" name="social_class" id="social_class" placeholder=" e.g ( facebook,google...)" required>
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


</body>
<script>
   document.getElementById('urlForm').addEventListener('submit', function(event) {
      var urlInput = document.getElementById('social_link');
      var urlValue = urlInput.value;
      var urlPattern = new RegExp('^(https?:\\/\\/)?' + // protocol
         '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|' + // domain name
         '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
         '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
         '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
         '(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator

      if (!urlPattern.test(urlValue)) {
         urlInput.classList.add('is-invalid');
         event.preventDefault();
      } else {
         urlInput.classList.remove('is-invalid');
      }
   });
</script>

</html>
@endsection