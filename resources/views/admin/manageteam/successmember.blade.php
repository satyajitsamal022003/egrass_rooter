@extends('layouts.admin.layout')
@section('section')

<!-- <body> -->
<!-- Main content -->
<div class="container-fluid">

  <!-- Row Starts -->
  <div class="row">
    <div class="col-sm-12 p-0">
      <div class="main-header">
        <h4>Team members</h4>
        <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="icofont icofont-home"></i></a>
          </li>
          <li class="breadcrumb-item"><a href="#">Team members</a>
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
                  <th>Role Name</th>
                  <th>Name</th>
                  <th>Phone Number</th>
                  <th>Email Id</th>
                  <th>Address</th>
                </tr>
              </thead>
              <tbody>
                @php($i=0)
                @if(isset($memberids))
                @foreach($memberids as $members)
                @php($memberList = App\Models\AddMember::where('id', $members)->orderBy('id', 'desc')->first())
                @php($roleDetails = App\Models\Role::where('id', $memberList->role_type)->first())

                <tr>
                    <td class="center">
                    <?php echo ++$i; ?>
                    </td>
                    <td class="center">
                        <?=$roleDetails->role;?>
                    </td>
                    <td class="center">
                        <?=$memberList->name;?>
                    </td>
                    <td class="center">
                        <?=$memberList->phone_number;?>
                    </td>
                    <td class="center">
                        <?=$memberList->email_id;?>
                    </td>
                    <td class="center">
                        <?=$memberList->address;?>
                    </td>
                </tr>
                @endforeach
                @endif

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