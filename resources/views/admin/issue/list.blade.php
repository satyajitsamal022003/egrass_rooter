@extends('layouts.admin.layout')
@section('section')

<!-- <body> -->
<!-- Main content -->
<div class="container-fluid">

  <!-- Row Starts -->
  <div class="row">
    <div class="col-sm-12 p-0">
      <div class="main-header">
        <h4>Manage Issue</h4>
        <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="icofont icofont-home"></i></a>
          </li>
          <li class="breadcrumb-item"><a href="#">Manage Issue</a>
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
                  <th>Voters</th>
                  <th>Issue</th>
                  <th>Created By</th>
                  <th>Created</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @php($i=0)
                @foreach ($issue as $sur)
                <tr>
                  <td>{{++$i}}</td>
                  <td><?php
                      $voters = $sur['voter'];
                      $voter = \App\Models\Voter::where('id', $voters)->orderBy('id', 'desc')->first();
                      ?>
                  </td>
                  <td>{{$sur->issue}}</td>
                  <td>
                    <?php
                    $user_id = $sur['user_id'];
                    $user = \App\Models\Campaign_user::where('id', $user_id)->orderBy('id', 'desc')->first();
                    ?>
                    @if($user)
                    {{ $user->first_name.' '.$user->first_name }}
                    @endif
                  </td>
                  <td>
                    @if (!empty($sur->created) && $sur->created != '0000-00-00 00:00:00')
                    {{ date('d-M-Y', strtotime($sur->created)) }}
                    @else
                    N/A
                    @endif
                  </td>

                  <td class="center">
                    <a class="btn btn-danger" href="{{route('reportissue.destroy',$sur->id)}}" onclick="return confirm('Are you sure to delete!');" title="Delete"><i class="fa fa-remove"></i></a>
                  </td>
                </tr>
                @endforeach

              </tbody>
              <tfoot>
                <tr>
                  <th>SL#</th>
                  <th>Voters</th>
                  <th>Issue</th>
                  <th>Created By</th>
                  <th>Created</th>
                  <th>Action</th>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- </body> -->
<!-- </html> -->
@endsection