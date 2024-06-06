<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Menu</title>
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
                           <form action="{{route('managemenu.store')}}" method="post">
                              @csrf
                              <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-3 col-form-label form-control-label">Menu Name*</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="text" name="menu_name" id="menu_name">
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="example-search-input" class="col-xs-3 col-form-label form-control-label">Assign Type*</label>
                                 <div class="col-sm-10">
                                    <input type="radio" name="assign_type" id="assign_type1" class="assign_input" value="1" checked=""> Page
                                    <input type="radio" name="assign_type" id="assign_type2" class="assign_input" value="2"> Custom Link
                                    <input type="radio" name="assign_type" id="assign_type3" class="assign_input" value="3"> Home Url
                                 </div>
                              </div>
                              <div class="form-group row" id="firstmenu">
                                 <label for="example-email-input" class="col-xs-3 col-form-label form-control-label">Menu Link</label>
                                 <div class="col-sm-10">
                                    <select class="form-control" name="menu_link1" id="menu_link1">
                                        <option value="">--Select Page--</option>
                                        @foreach (App\Models\Page::where('is_active',1)->get() as $pages)
                                        <option value="{{$pages->id}}">{{$pages->page_name}}</option>
                                        @endforeach
                                    </select>
                                 </div>
                              </div>
                              <div class="form-group row" id="secondmenu">
                                 <label for="example-email-input" class="col-xs-3 col-form-label form-control-label">Menu Link</label>
                                 <div class="col-sm-10">
                                 <input type="text" name="menulink2" id="menulink2" class="form-control">
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="example-email-input" class="col-xs-3 col-form-label form-control-label">Menu Position</label>
                                 <div class="col-sm-10">
                                    <select class="form-control" name="menuposition" id="menuposition">
                                        <option value="">--Select--</option>
                                        <option value="1">Main Menu</option>
                                        <option value="2">Copyright Menu</option>
                                        <option value="3">Footer Menu</option>
                                    </select>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="example-email-input" class="col-xs-3 col-form-label form-control-label">Select Parent</label>
                                 <div class="col-sm-10">
                                    <select class="form-control" name="parent" id="parent">
                                        <option value="">None</option>
                                       @foreach (App\Models\Menu::all() as $parentlist)
                                        <option value="{{$parentlist->id}}">{{$parentlist->menu_name}}</option>
                                       @endforeach
                                    </select>
                                 </div>
                              </div>
                              <div class="form-group row">
                                 <label for="example-text-input" class="col-xs-3 col-form-label form-control-label">Order No</label>
                                 <div class="col-sm-10">
                                    <input class="form-control" type="number" name="orderno" id="orderno" min="0">
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
         <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- show hide -->
 <script>
      $(document).ready(function(){
         $("#firstmenu").show(); // Show firstmenu for other values
          $("#secondmenu").hide();
        $(".assign_input").change(function(){
            var assignType = $(this).val();
            if (assignType == '1') {
                $("#firstmenu").show();
                $("#secondmenu").hide();
            } else if (assignType == '2') {
                $("#firstmenu").hide();
                $("#secondmenu").show();
            } else if (assignType == '3') {
                $("#firstmenu").hide();
                $("#secondmenu").hide();
            } else {
                $("#firstmenu").show(); // Show firstmenu for other values
                $("#secondmenu").hide();
            }
        });
    });
</script>



</html>
@endsection
