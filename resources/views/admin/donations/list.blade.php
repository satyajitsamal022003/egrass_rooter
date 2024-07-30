@extends('layouts.admin.layout')
@section('section')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<!-- <body> -->
<!-- Main content -->
<div class="container-fluid">

  <!-- Row Starts -->
  <div class="row">
    <div class="col-sm-12 p-0">
      <div class="main-header">
        <h4>Donations</h4>
        <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="icofont icofont-home"></i></a>
          </li>
          <li class="breadcrumb-item"><a href="#">Donations</a>
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
                  <th>Full name</th>
                  <th>Email ID</th>
                  <th>Donated Party</th>
                  <th>Donation Purpose</th>
                  <th>Amount</th>
                  <th>Status</th>
                  <th>Donation Date</th>
                </tr>
              </thead>
              <tbody>
                @php($i=0)
                @foreach ($donations as $donate)
                <tr>
                  <td>{{++$i}}</td>
                  <td>{{$donate->full_name}}</td>
                  <td>{{$donate->email}}</td>
                  <td>
                    @php($party = App\Models\Party::where('id',$donate->party)->first())
                    {{$party?$party->party_name?$party->party_name:'N/A':'N/A'}}
                </td>
                  <td>{{$donate->donation_purpose?$donate->donation_purpose:'N/A'}}</td>
                  <td>{{$donate->amount?$donate->amount:$donate->other_amount}}</td>
                  <td class="status">
                    <label class="switch">
                      <input type="checkbox" onclick="catstatus({{$donate->id}})" <?php if ($donate->is_active == '1') {
                                                                                      echo "checked";
                                                                                    } ?>>
                      <span class="slider"></span>
                    </label>
                  </td>
                  <td>
                    @if (!empty($donate->created) && $donate->created != '0000-00-00 00:00:00')
                    {{ date('d-M-Y', strtotime($donate->created)) }}
                    @else
                    N/A
                    @endif
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
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->

<script>
  function catstatus(id) {
    $.ajax({
      url: "{{ route('donation.status')}}",
      type: "POST",
      data: {
        "_token": "{{ csrf_token() }}",
        id: id
      },
      success: function(response) {
        if (response.code == 200) {
          Swal.fire('Status Updated', '', 'success');
        }
      }
    })
  }
</script>
<!-- </body> -->
<!-- </html> -->
@endsection