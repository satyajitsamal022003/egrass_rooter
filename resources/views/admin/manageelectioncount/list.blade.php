<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Election Voters</title>
</head>
@extends('layouts.admin.layout')
@section('section')
<body>
<div class="container-fluid">
 
 <!-- Row Starts -->
 <div class="row">
     <div class="col-sm-12 p-0">
         <div class="main-header">
         <h4>Manage Election Votes</h4>
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
 <a href="{{route('manageelectionvoters.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> </a>
 <!-- /.card-header -->
 <div class="card-body">
   <table id="datatable" class="table table-bordered table-striped">
     <thead>
     <tr>
       <th>SL#</th>
       <th>State Name</th>
       <th>Party Name</th>
       <th>Vote Value</th>
       <th>Created Date</th>
     </tr>
     </thead>
     <tbody>
        @php($i=0)
        @foreach ($partyvoteslist as $partylist)
        <tr>
        <td>{{++$i}}</td>
        <td>
            @php($state=App\Models\State::where('id',$partylist->state_id)->first())
            {{$state->state}}
        </td>
        <td>
            @php($party = App\Models\Party::where('id',$partylist->party_id)->first())
            {{$party->party_name}}</td>
        <td>{{$partylist->vote_value}}</td>
        <td>  @if (!empty($partylist->created_at) && $partylist->created_at != '0000-00-00 00:00:00')
                    {{ date('d-M-Y', strtotime($partylist->created_at)) }}
                @else 
                    N/A
                @endif
        </td>
        </tr>
        @endforeach
     </tbody>
     <tfoot>
     <tr>
       <th>SL#</th>
       <th>State Name</th>
       <th>Party Name</th>
       <th>Vote Value</th>
       <th>Created Date</th>
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