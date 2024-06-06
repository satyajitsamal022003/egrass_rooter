<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit State Districts</title>
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
                           <h5 class="card-header-text">Edit State Districts</h5>
                        </div>
                        <div class="card-block">
                           <form method="post" action="{{route('stateconst.update',$statconst->id)}}">
                            @csrf
                              <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">State</label>
                                 <div class="col-sm-10">
                                    <select name="state" id="state" class="form-control">
                                        <option value="">Select State</option>
                                        @foreach (App\Models\State::all() as $statedata )
                                           <option value="{{$statedata->id }}" {{$statconst->state_id==$statedata->id?'selected':''}}>{{$statedata->state }}</option>
                                        @endforeach
                                    </select>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">State Constituency Name *</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="text" name="state_const" id="state_const" value="{{$statconst->state_constituency }}" required>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Code *</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="text" name="code" id="code" value="{{$statconst->code }}" required>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Composition*</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="text" name="composition" id="composition" value="{{$statconst->composition }}" required>
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
