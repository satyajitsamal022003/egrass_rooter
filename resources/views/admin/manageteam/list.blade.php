@extends('layouts.admin.layout')
@section('section')

<!-- <body> -->
<!-- Main content -->
<div class="container-fluid">

  <!-- Row Starts -->
  <div class="row">
    <div class="col-sm-12 p-0">
      <div class="main-header">
        <h4>Manage Team</h4>
        <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="icofont icofont-home"></i></a>
          </li>
          <li class="breadcrumb-item"><a href="#">Manage Team</a>
          </li>
          <li class="breadcrumb-item"><a>List</a>
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
                  <th>Name</th>
                  <th>Description</th>
                  <!-- <th>Assign member</th> -->
                  <th>Created By</th>
                  <th>Created</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @php($i=0)
                @foreach ($team as $tl)
                <tr>
                  <td>{{++$i}}</td>
                  <td>{{$tl->name}}</td>
                  <td>{{$tl->description}}</td>
                  <!-- <td></td> -->
                  <td>
                    <?php
                    $user_id = $tl['user_id'];
                    $user = \App\Models\Campaign_user::where('id', $user_id)->orderBy('id', 'desc')->first();
                    ?>
                    @if($user)
                    {{ $user->first_name.' '.$user->first_name }}
                    @else
                    {{'N/A'}}
                    @endif
                  </td>
                  <td>
                    @if (!empty($tl->created) && $tl->created != '0000-00-00 00:00:00')
                    {{ date('d-M-Y', strtotime($tl->created)) }}
                    @else
                    N/A
                    @endif
                  </td>

                  <td class="center">
                    <a class="btn btn-info" href="{{route('manageteam.sucessmember',$tl->id)}}" title="Team Member"><i class="fa fa-user"></i></a>
                    <a class="btn btn-danger" href="{{route('manageteam.destroy',$tl->id)}}" onclick="return confirm('Are you sure to delete!');" title="Delete"><i class="fa fa-remove"></i></a>
                  </td>
                </tr>
                @endforeach

              </tbody>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- </body> -->
<!-- </html> -->
@endsection