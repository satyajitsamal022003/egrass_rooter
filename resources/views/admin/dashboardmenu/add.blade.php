<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Add Menu</title>
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
                           <h5 class="card-header-text">Add Menu</h5>
                        </div>
                        <div class="card-block">
                           <form>
                              <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Menu Name *</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="text" name="menuname" id="menuname">
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Menu Type</label>
                                 <div class="col-sm-10">
                                    <select name="menu_type" id="menu_type" class="form-control">
                                        <option value="">None</option>
                                        <option value=""></option>
                                    </select>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Menu Link</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="text" name="menu_link" id="menu_link">
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="example-email-input" class="col-xs-2 col-form-label form-control-label">Select Parent</label>
                                 <div class="col-sm-10">
                                    <select class="form-control" name="parent" id="parent">
                                        <option value="">None</option>
                                        <option value="" ></option>
                                    </select>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Menu Class</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="text" name="menu_class" id="menu_class">
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Order No</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="text" name="order_no" id="order_no">
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
                           </form>
                        </div>
                     </div>
                  </div>
             
            </div>

            <!-- Main content ends -->
         </div>
</html>
@endsection
