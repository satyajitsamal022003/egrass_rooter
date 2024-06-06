<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Wards</title>
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
                           <h5 class="card-header-text">Edit Wards</h5>
                        </div>
                        <div class="card-block">
                           <form action="{{route('manageward.update',$editward->id)}}" method="post">
                              @csrf
                              <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Lga *</label>
                                 <div class="col-sm-10">
                                    <select name="lga" id="lga" class="form-control" required>
                                        <option value="">Select Lga</option>
                                        @foreach (App\Models\Local_constituency::all() as $lgsdata )
                                        <option value="{{$lgsdata->id }}" {{$editward->lga_id==$lgsdata->id?'selected':''}}>{{$lgsdata->lga}}</option>
                                        @endforeach
                                    </select>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Ward Name *</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="text" name="wardname" id="wardname" value="{{$editward->ward_details}}" required>
                                 </div>
                                 </div>
                                 <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Ward No *</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="number" name="wardno" value="{{$editward->ward_no}}" id="wardno" min="0" required>
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
