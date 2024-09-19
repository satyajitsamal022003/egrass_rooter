
@extends('layouts.admin.layout')
@section('section')
<!-- <body> -->
     <!-- Main content --> 
 <div class="container-fluid">
 
            <!-- Row Starts -->
            <div class="row">
                <div class="col-sm-12 p-0">
                    <div class="main-header">
                    <h4>Manage Party</h4>
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
          <form id="filter-form" method="get" class="form-horizontal">
          
         
            <div class="col-sm-4">
                <input type="search" name="searchtxt" id="searchtxt" placeholder=" Search by Party" value="" class="form-control">
            </div>
            <div class="col-sm-1">
                <button type="button" id="filter-btn" class="btn btn-info search">Filter</button>
            </div>
            <div class="col-sm-1">
                <button type="button" id="reset-btn" class="btn btn-info search" onclick="resetPage()">Reset</button>
            </div>
         </form>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="datatable" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>SL#</th>
                  <th>Party</th>
                  <th>Party chairman name</th>
                  <!-- <th>Logo</th> -->
                  <th>Status</th>
                  <th>Created</th>
                  <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    @php($i=0)
                @foreach ($partylist as $party)
                 <tr>
                  <td>{{++$i}}</td>
                  <td>{{$party->party_name}}</td>
                  <td>{{$party->owner_name}}</td>
                  <!-- <td>
                  <img class="img-fluid mt-2" style="height: 100px;width: 165px;" src="{{ asset('images/parties/' . $party->party_img) }}" alt="Preview">
                  </td> -->
                  <td>
                  <button id="change-status-btn" data-url="" data-status="{{ $party->is_active }}">
                    @if($party->is_active=='1')
                    <span class="badge badge-success">Publish</span>
                    @else
                    <span class="badge badge-warning">Un Publish</span>
                    @endif
                  </button>
                  </td>
                  <td>{{date('d-M-Y',strtotime($party->created_at))}}</td>
                  <td class="center">
                  <a class="btn btn-success" href="#" data-toggle="modal" data-target="#view<?php echo $party->id ?>" title="View"><i class="fa fa-file-text" aria-hidden="true"></i></a>
                      <a class="btn btn-info" href="{{route('manageparty.edit',$party->id)}}" title="Edit"><i class="fa fa-edit"></i></a>
                    <a class="btn btn-danger" href="{{route('manageparty.destroy',$party->id)}}" onclick="return confirm('Are you sure to delete!');" title="Delete"><i class="fa fa-remove"></i></a>
                    </td>

                     <!-- modal starts -->
                     <div id="view<?php echo $party->id ?>" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                      <h4 class="modal-title" id="myModalLabel">Party Details</h4>
                                    </div>
                                    <div class="modal-body" style="padding:0px;">
                                      <div class="col-md-12">
                                        <div class="">
                                          <div class="control-group">
                                            <label for="slug" class="col-lg-3 control-label"><b>Party Name :</b></label>
                                            <div class="col-lg-9">
                                              <?php echo $party->party_name ?>
                                            </div>
                                          </div>
                                          <div class="clearfix"></div>
                                          <div class="control-group">
                                            <label for="description" class="col-lg-3 control-label"><b>party chairman name :</b></label>
                                            <div class="col-lg-9">
                                              <?php echo $party->owner_name ?>
                                            </div>
                                          </div>


                                          <div class="clearfix"></div>
                                          <div class="control-group">
                                            <label for="description" class="col-lg-3 control-label"><b>Created :</b></label>
                                            <div class="col-lg-9">
                                              <?php echo date("d-m-Y", strtotime($party->created)); ?>
                                            </div>
                                          </div>

                                          <div class="clearfix"></div>
                                          <div class="control-group">
                                            <label for="description" class="col-lg-3 control-label"><b>Status :</b></label>
                                            <div class="col-lg-9">
                                              <?php if ($party->is_active == 1) {
                                              ?>
                                                <span class="btn btn-success">Publish</span>
                                              <?php } else { ?>
                                                <span class="btn btn-warning">Unpublish</span>
                                              <?php } ?>
                                            </div>
                                          </div>

                                          <div class="clearfix"></div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                                    </div>

                                  </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                              </div>
                              <!-- modal ends -->
                </tr>
                 @endforeach
              
                </tbody>
                <tfoot>
                <tr>
                  <th>SL#</th>
                  <th>Party</th>
                  <th>Party chairman name</th>
                  <!-- <th>Logo</th> -->
                  <th>Status</th>
                  <th>Created</th>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
  $(document).ready(function() {
    // Change status button click event
    $(document).on('click', '.change-status-btn', function() {
        var url = $(this).data('url');
        var newStatus = $(this).data('status') === 'active' ? 'inactive' : 'active';
        
        $.ajax({
            url: url,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}', status: newStatus },
            success: function(response) {
                // Handle success response
                console.log(response);
                $('#status-message').text('Status changed successfully!');
                // Update button text or status display if needed
                $(this).data('status', newStatus);
                $(this).text('Change Status to ' + newStatus);
            },
            error: function(xhr) {
                // Handle error response
                console.log(xhr.responseText);
                $('#status-message').text('Error occurred while changing status.');
            }
        });
    });
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButton = document.getElementById('filter-btn');
        const resetButton = document.getElementById('reset-btn');
        const searchInput = document.getElementById('searchtxt');

        filterButton.addEventListener('click', function() {
            const searchTerm = searchInput.value;

            $.ajax({
                url: "{{ route('manageparty.list') }}",
                method: "GET",
                data: { searchtxt: searchTerm },
                success: function(response) {
                    $('#party-list').html(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

        resetButton.addEventListener('click', function() {
            searchInput.value = '';
            $.ajax({
                url: "{{ route('manageparty.list') }}",
                method: "GET",
                data: { searchtxt: '' },
                success: function(response) {
                    $('#party-list').html(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });

         // Reset button click event
   
    });
</script>

<script>
    function resetPage() {
        location.reload();
    }
</script>

<!-- </body> -->
<!-- </html> -->
@endsection
