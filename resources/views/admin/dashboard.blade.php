<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
@extends('layouts.admin.layout')
@section('section')
<body>
     <!-- Main content starts -->
     <div class="container-fluid">
            <div class="row">
               <div class="main-header">
                  <h4>Welcome To Egrassrooter</h4><br>
                  <p>Here's what's happening with your dashboard today.</p>
               </div>
            </div>
            <!-- 4-blocks row start -->
            <div class="row dashboard-header">
               <div class="col-lg-3 col-md-6">
                  <div class="card dashboard-product">
                     <span>Registered Users</span>
                     <h2 class="dashboard-total-products">{{$registeredusers->count()}}</h2>
                     <span class="label label-warning">Sales</span>Arriving Today
                     <div class="side-box">
                     <i class="ti-user text-warning-color"></i>
                     </div>  
                  </div>
               </div>
               <div class="col-lg-3 col-md-6">
                  <div class="card dashboard-product">
                     <span>Verified Users</span>
                     <h2 class="dashboard-total-products">{{$verifiedusers}}</h2>
                     <span class="label label-primary">Views</span>View Today
                     <div class="side-box ">
                        <i class="ti-check text-info-color"></i> 
                     </div>
                  </div>
               </div>
               <div class="col-lg-3 col-md-6">
                  <div class="card dashboard-product">
                     <span>Active Users</span>
                     <h2 class="dashboard-total-products"><span>{{$activeuser}}</span></h2>
                     <span class="label label-success">Sales</span>Reviews
                     <div class="side-box">
                           <i class="ti-thumb-up text-success"></i> <!-- Thumbs-up icon -->
                     </div>
                  </div>
               </div>
               <div class="col-lg-3 col-md-6">
                  <div class="card dashboard-product">
                     <span>No. of Parties</span>
                     <h2 class="dashboard-total-products"><span>{{$parties->count()}}</span></h2>
                     <span class="label label-danger">Sales</span>Reviews
                     <div class="side-box">
                        <i class="ti-book text-danger-color"></i>
                     </div>
                  </div>
               </div>
            </div>
            <!-- 4-blocks row end -->

            <!-- 1-3-block row start -->
          
            <!-- 1-3-block row end -->

            <!-- 2-1 block start -->
            <div class="row">
            <div class="col-xl-6 col-lg-12">
               <div class="card">
                  <div class="card-block">
                        <div class="table-responsive">
                           <!-- Add the header here -->
                           <h3 style="font-size: large;">Campaign Manager List</h3>
                           <hr>
                           <table class="table m-b-0 photo-table">
                              <thead>
                                    <tr class="text-uppercase">
                                       <th>Name</th>
                                       <th>Mail Id</th>
                                       <th>Phone No</th>
                                    </tr>
                              </thead>
                              <tbody>
                                    @foreach($campaignManagers as $manager)
                                    <tr>
                                       <td>{{ $manager->first_name.' '.$manager->last_name }}
                                          <p><i class="icofont icofont-clock-time"></i>Created
                                             @if (!empty($manager->created) && $manager->created != '0000-00-00 00:00:00')
                                                   {{ date('d-M-Y', strtotime($manager->created)) }}
                                             @else 
                                                   N/A
                                             @endif
                                          </p>
                                       </td>
                                       <td>{{ $manager->email_id }}</td>
                                       <td>{{ $manager->telephone }}</td>
                                    </tr>
                                    @endforeach
                              </tbody>
                           </table>
                        </div>
                  </div>
               </div>
           </div>

               <div class="col-xl-6 col-lg-12">
                  <div class="card">
                     <div class="card-block">
                        <div class="table-responsive">
                           
                        <h3 style="font-size: large;">Manage Polling Unit</h3>
                           <hr>
                           <table class="table m-b-0 photo-table">
                              <thead>
                                 <tr class="text-uppercase">
                                    <th>#sl</th>
                                    <th>State</th>
                                    <th>Polling Name</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @foreach($pollingList as $index => $pollingUnit)
                                 <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $pollingUnit->state_name }}</td>
                                    <td>{{ $pollingUnit->polling_name }}</td>
                                    <td>
                                          @if (!empty($pollingUnit->created_at) && $pollingUnit->created_at != '0000-00-00 00:00:00')
                                                {{ date('d-M-Y', strtotime($pollingUnit->created_at)) }}
                                          @else 
                                                N/A
                                          @endif
                                    </td>
                                    <td>
                                       <a href="{{ route('managepollings.edit', $pollingUnit->id) }}" class="btn btn-info"><i class="fa fa-edit"></i></a>
                                       <a class="btn btn-danger" href="{{route('managepollings.destroy',$pollingUnit->id)}}" onclick="return confirm('Are you sure to delete!');" title="Delete">  <i class="fa fa-remove"></i> </a>
                                    </td>
                                 </tr>
                                 @endforeach
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>

            <div class="row">
            <div class="col-xl-12 col-lg-12">
                  <div class="card">
                     <div class="card-block">
                        <div class="table-responsive">
                        <h3 style="font-size: large;">Manage Service</h3>
                           <hr>
                           <table class="table m-b-0 photo-table">
                              <thead>
                                 <tr class="text-uppercase">
                                    <th>SL#</th>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>Service Class</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                 </tr>
                              </thead>
                              <tbody>
                            @foreach($allServices as $index => $service)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $service->title }}</td>
                                <td>{{ $service->content }}</td>
                                <td>
                                 @if ($service->icon)
                                 <i class="fa fa-<?php echo $service['icon'];?>"></i>
                                 @else
                                 N/A
                                 @endif
                                 </td>
                                <td>
                                 @if ($service->is_active==1)
                                    <span class="badge badge-success">Active</span>
                                 @else
                                    <span class="badge badge-danger">In Active</span>
                                 @endif
                                </td>
                                <td> @if (!empty($service->created_at) && $service->created_at != '0000-00-00 00:00:00')
                                          {{ date('d-M-Y', strtotime($service->created_at)) }}
                                    @else 
                                          N/A
                                    @endif
                                 </td>
                                <td>
                                    <a href="{{ route('manageservices.edit', $service->id) }}" class="btn btn-info"><i class="fa fa-edit"></i></a>
                                    <a href="{{ route('manageservices.destroy', $service->id) }}" class="btn btn-danger" onclick="return confirm('Are you sure to delete!');" title="Delete"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>

            <div class="row">
               <div class="col-xl-4 col-lg-12">
                  <div class="card">
                     <div class="card-header">
                        <h5 class="card-header-text">Survey Response</h5>
                     </div>
                     <div class="card-block">
                        <div id="piechart" style="min-width: 250px; height: 460px; margin: 0 auto"></div>
                     </div>
                  </div>
               </div>

               <div class="col-lg-8">
                  <div class="card">
                     <div class="card-header">
                        <h5 class="card-header-text">Bar chart</h5>
                     </div>
                     <div class="card-block">
                        <div id="barchart" style="min-width: 250px; height: 330px; margin: 0 auto"></div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- 2-1 block end -->
         </div>
         <!-- Main content ends -->

</body>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
</html>
@endsection
