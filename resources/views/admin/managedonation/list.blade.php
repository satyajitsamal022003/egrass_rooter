@extends('layouts.admin.layout')
@section('section')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>


<style>
  /* The switch - the box around the slider */
  .switch {
    position: relative;
    display: inline-block;
    width: 40px;
    /* Reduced width */
    height: 20px;
    /* Reduced height */
  }

  /* Hide default HTML checkbox */
  .switch input {
    opacity: 0;
    width: 0;
    height: 0;
  }

  /* The slider */
  .slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #ccc;
    -webkit-transition: .4s;
    transition: .4s;
  }

  .slider:before {
    position: absolute;
    content: "";
    height: 16px;
    /* Reduced height */
    width: 16px;
    /* Reduced width */
    left: 2px;
    /* Adjusted position */
    bottom: 2px;
    /* Adjusted position */
    background-color: white;
    -webkit-transition: .4s;
    transition: .4s;
  }

  input:checked+.slider {
    background-color: #2196F3;
  }

  input:focus+.slider {
    box-shadow: 0 0 1px #2196F3;
  }

  input:checked+.slider:before {
    -webkit-transform: translateX(20px);
    /* Adjusted translation */
    -ms-transform: translateX(20px);
    /* Adjusted translation */
    transform: translateX(20px);
    /* Adjusted translation */
  }

  /* Rounded sliders */
  .slider.round {
    border-radius: 20px;
    /* Adjusted to match new height */
  }

  .slider.round:before {
    border-radius: 50%;
  }
</style>



<!-- <body> -->
<!-- Main content -->
<div class="container-fluid">

  <!-- Row Starts -->
  <div class="row">
    <div class="col-sm-12 p-0">
      <div class="main-header">
        <h4>Donation</h4>
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
                  <th>Political Party Name</th>
                  <th>Candidate Name</th>
                  <th>Donor Full Name</th>
                  <th>Donor Email ID</th>
                  <th>Donation Purpose</th>
                  <th>Donation Amount(in ₦)</th>
                  <th>Payment Status</th>
                  <th>Donation Date</th>
                  <!-- <th>Actions</th> -->
                </tr>
              </thead>
              <tbody>
                @php($i=0)
                @foreach ($donationlist as $donate)
                <tr>
                  <td>{{++$i}}</td>
                  <td>
                  @php($partyname = App\Models\Party::first())  
                  {{$partyname->party_name}}</td>
                  <td>{{$partyname->candidate_name ?? 'N/A'}}</td>
                  <td>{{$donate->full_name}}</td>
                  <td>{{$donate->email}}</td>
                  <td>{{$donate->donation_purpose}}</td>
                  <td>{{$donate->amount}}</td>
                  <td>@if($donate->is_active == 1)
                      <span class="badge badge-info">Success</span>
                      @elseif($donate->is_active == 2)
                      <span class="badge badge-danger">Failed</span>
                      @else
                      <span class="badge badge-warning">Pending</span>
                      @endif
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
  function catstatus(id) {
    $.ajax({
      url: "{{ route('managefaqs.status')}}",
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