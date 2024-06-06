<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Campaigns</title>
</head>
@extends('layouts.admin.layout')
@section('section')
<body>
<div class="container-fluid">
 
 <!-- Row Starts -->
 <div class="row">
     <div class="col-sm-12 p-0">
         <div class="main-header">
         <h4>Campaign Manager List</h4>
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

<section class="content">
<div class="row">
<div class="col-12">

<div class="card" style="padding: 20px;">
 <!-- <div class="card-header">
   <h3 class="card-title">Manage Pages/Content</h3>
 </div> -->
 <!-- /.card-header -->
 <div class="card-body">
   <table id="datatable" class="table table-bordered table-striped">
     <thead>
     <tr>
       <th>SL#</th>
       <th>Name</th>
       <th>Mail Id</th>
       <th>Phone No</th>
       <th>Actions</th>
     </tr>
     </thead>
     <tbody>
    @php($i=0)
    @foreach (App\Models\Campaign_user:: all() as $campuser)
      <tr>
       <td>{{++$i}}</td>
       <td>{{$campuser->first_name.' '.$campuser->last_name}}</td>
       <td>{{$campuser->email_id}}</td>
       <td>{{$campuser->telephone}}</td>
       <td class="center"> 
         <a class="btn btn-info" href="{{route('managecampaign.view',$campuser->id)}}" title="Edit"><i class="fa fa-eye"></i></a>
      </td>
     </tr>
   @endforeach
   
     </tbody>
     <tfoot>
     <tr>
       <th>SL#</th>
       <th>Name</th>
       <th>Mail Id</th>
       <th>Phone No</th>
       <th>Actions</th>
     </tr>
     </tfoot>
   </table>
 </div>
 <!-- /.card-body -->
</div>
<!-- /.card -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</section>
</div>
</body>
</html>
@endsection