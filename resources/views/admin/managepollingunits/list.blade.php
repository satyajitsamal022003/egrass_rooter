 
@extends('layouts.admin.layout')
@section('section')
<!-- <body> -->
     <!-- Main content --> 
 <div class="container-fluid">
 
            <!-- Row Starts -->
            <div class="row">
                <div class="col-sm-12 p-0">
                    <div class="main-header">
                    <h4>Manage Polling Unit</h4>
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
            <!-- /.card-header -->
            <div class="card-body">
              <table id="datatable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>SL#</th>
                  <th>State</th>
                  <th>LGA</th>
                  <th>Ward</th>
                  <th>Polling Name</th>
                  <th>Polling Capacity</th>
                  <th>Created Date</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    @php($i=0)
                @foreach ($pollongunit as $polling)
                 <tr>
                  <td>{{++$i}}</td>
                  <td>{{$polling->state_name}}</td>
                  <td>{{$polling->lga}}</td>
                  <td>{{$polling->ward_id}}</td>
                  <td>{{$polling->polling_name}}</td>
                  <td>{{$polling->polling_capacity}}</td>
                  <td>  @if (!empty($polling->created_at) && $polling->created_at != '0000-00-00 00:00:00')
                        {{ date('d-M-Y', strtotime($polling->created_at)) }}
                        @else 
                            N/A
                        @endif
                  </td>
                  <td class="center">
                      <a class="btn btn-info" href="{{route('managepollings.edit',$polling->id )}}" title="Edit"><i class="fa fa-edit"></i></a>
                      <a class="btn btn-danger" href="{{route('managepollings.destroy',$polling->id )}}" onclick="return confirm('Are you sure to delete!');" title="Delete"><i class="fa fa-remove"></i></a>
                  </td>
                </tr>
              @endforeach
              
                </tbody>
                <tfoot>
                <tr>
                  <th>SL#</th>
                  <th>State</th>
                  <th>LGA</th>
                  <th>Ward</th>
                  <th>Polling Name</th>
                  <th>Polling Capacity</th>
                  <th>Created Date</th>
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
<!-- </body> -->
<!-- </html> -->
@endsection
