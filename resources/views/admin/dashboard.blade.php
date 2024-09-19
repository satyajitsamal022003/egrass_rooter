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
   <style>
      .chart-container {
         position: relative;
         height: 400px;
         /* Adjust this as needed */
         overflow: hidden;
      }

      #chart20 {
         height: 100%;
         width: 100%;
      }
   </style>
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
               <span class="label label-warning">Registered</span>
               <div class="side-box">
                  <i class="ti-user text-warning-color"></i>
               </div>
            </div>
         </div>
         <div class="col-lg-3 col-md-6">
            <div class="card dashboard-product">
               <span>Verified Users</span>
               <h2 class="dashboard-total-products">{{$verifiedusers}}</h2>
               <span class="label label-primary">Verified</span>
               <div class="side-box ">
                  <i class="ti-check text-info-color"></i>
               </div>
            </div>
         </div>
         <div class="col-lg-3 col-md-6">
            <div class="card dashboard-product">
               <span>Active Users</span>
               <h2 class="dashboard-total-products"><span>{{$activeuser}}</span></h2>
               <span class="label label-success">Available</span>
               <div class="side-box">
                  <i class="ti-thumb-up text-success"></i> <!-- Thumbs-up icon -->
               </div>
            </div>
         </div>
         <div class="col-lg-3 col-md-6">
            <div class="card dashboard-product">
               <span>No. of Parties</span>
               <h2 class="dashboard-total-products"><span>{{$parties->count()}}</span></h2>
               <span class="label label-danger">Party</span>
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
                                 <a class="btn btn-danger" href="{{route('managepollings.destroy',$pollingUnit->id)}}" onclick="return confirm('Are you sure to delete!');" title="Delete"> <i class="fa fa-remove"></i> </a>
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
                                 <i class="fa fa-<?php echo $service['icon']; ?>"></i>
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
         <div class="col-md-12">
            <div class="card">
               <div class="white-card-body chart-container">
                  <canvas id="chart20"></canvas>
               </div>
            </div>
         </div>

         <!-- <div class="col-md-12">
            <div class="white-card-body">
               <h5>Election Results Filter</h5>
               <form id="electionFilterForm">
                  <div class="form-group">
                     <label for="electionType">Election Type</label>
                     <select id="electionType" class="form-control" name="election_type">
                        @foreach($electionTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->type }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="form-group" id="stateSelect">
                     <label for="state">State</label>
                     <select id="state" class="form-control" name="state_id">
                        @foreach($states as $state)
                        <option value="{{ $state->id }}">{{ $state->state }}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="form-group">
                     <label for="electionYear">Election Year</label>
                     <select id="electionYear" class="form-control" name="election_year">
                        @for($year = 2003; $year <= date('Y'); $year++)
                           <option value="{{ $year }}">{{ $year }}</option>
                           @endfor
                     </select>
                  </div>
                  <button type="submit" id="filterButton" class="btn btn-primary">Filter</button>
               </form>
            </div>
         </div>

         <div class="col-lg-12">
            <div class="card">
               <div class="card-header">
                  <h5 class="card-header-text">Bar chart</h5>
               </div>
               <div class="card-block">
                  <canvas id="electionResultsChart"></canvas>
               </div>
            </div>
         </div> -->
      </div>
      <!-- 2-1 block end -->
   </div>
   <!-- Main content ends -->

</body>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script type="text/javascript">
   var surveyTitles = @json($surveyTitles);
   var surveyCounts = @json($surveyCounts);

   var ctx = document.getElementById('chart20').getContext('2d');
   var chart = new Chart(ctx, {
      type: 'bar',
      data: {
         labels: surveyTitles,
         datasets: [{
               label: 'Poor',
               data: surveyCounts.map(counts => counts.poor),
               backgroundColor: 'rgba(255, 99, 132, 0.2)', // Light Red
               borderColor: 'rgba(255, 99, 132, 1)', // Dark Red
               borderWidth: 1
            },
            {
               label: 'Average',
               data: surveyCounts.map(counts => counts.average),
               backgroundColor: 'rgba(255, 159, 64, 0.2)', // Light Orange
               borderColor: 'rgba(255, 159, 64, 1)', // Dark Orange
               borderWidth: 1
            },
            {
               label: 'Good',
               data: surveyCounts.map(counts => counts.good),
               backgroundColor: 'rgba(255, 205, 86, 0.2)', // Light Yellow
               borderColor: 'rgba(255, 205, 86, 1)', // Dark Yellow
               borderWidth: 1
            },
            {
               label: 'Very Good',
               data: surveyCounts.map(counts => counts.verygood),
               backgroundColor: 'rgba(75, 192, 192, 0.2)', // Light Teal
               borderColor: 'rgba(75, 192, 192, 1)', // Dark Teal
               borderWidth: 1
            },
            {
               label: 'Outstanding',
               data: surveyCounts.map(counts => counts.outstanding),
               backgroundColor: 'rgba(0, 128, 0, 0.2)', // Light Green
               borderColor: 'rgba(0, 128, 0, 1)', // Dark Green
               borderWidth: 1
            }
         ]


      },
      options: {
         responsive: true,
         maintainAspectRatio: false,
         plugins: {
            legend: {
               display: true,
               position: 'top'
            },
            title: {
               display: true,
               text: 'Survey Responses',
               font: {
                  size: 18
               }
            }
         },
         scales: {
            x: {
               title: {
                  display: true,
                  text: 'Survey Titles'
               },
               ticks: {
                  maxRotation: 45,
                  minRotation: 45
               },
               barPercentage: 0.8, // Adjust this value to control bar width
               categoryPercentage: 0.9 // Adjust this value to control the space between bars
            },
            y: {
               title: {
                  display: true,
                  text: 'Number of Responses',
                  font: {
                     size: 14,
                     weight: 'bold' // Make the y-axis title bold as well
                  }
               },
               beginAtZero: true
            }
         }
      }
   });
</script>

<script type="text/javascript">
   // Handle election type change to hide/show the state field
   document.getElementById('electionType').addEventListener('change', function() {
      var electionType = this.value;
      if (electionType == 1) {
         document.getElementById('stateSelect').style.display = 'none';
      } else {
         document.getElementById('stateSelect').style.display = 'block';
      }
   });

   // Handle filter button click
   document.getElementById('filterButton').addEventListener('click', function() {
      var electionType = document.getElementById('electionType').value;
      var state = document.getElementById('state').value;
      var electionYear = document.getElementById('electionYear').value;

      fetch(`/get-election-results?election_type=${electionType}&state_id=${state}&election_year=${electionYear}`)
         .then(response => response.json())
         .then(data => {
            renderChart(data.stateNames, data.voteValues);
         });
   });

   // Function to render the election results chart
   function renderChart(stateNames, voteValues) {
      var ctx = document.getElementById('electionResultsChart').getContext('2d');
      new Chart(ctx, {
         type: 'bar',
         data: {
            labels: stateNames,
            datasets: [{
               label: 'Votes',
               data: voteValues,
               backgroundColor: 'rgba(75, 192, 192, 0.2)',
               borderColor: 'rgba(75, 192, 192, 1)',
               borderWidth: 1
            }]
         },
         options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
               x: {
                  title: {
                     display: true,
                     text: 'States'
                  }
               },
               y: {
                  title: {
                     display: true,
                     text: 'Vote Value'
                  },
                  beginAtZero: true
               }
            }
         }
      });
   }
</script>





</html>
@endsection