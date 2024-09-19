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
        <h4>Manage Pages/Content</h4>
        <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
          <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="icofont icofont-home"></i></a>
          </li>
          <li class="breadcrumb-item"><a href="#">Manage Pages</a>
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

        <div class="card" style="padding: 10px;">
        <a href="{{route('pages.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> </a>

          <!-- /.card-header -->
          <div class="card-body" style="padding: 23px;">
            <table id="datatable" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>SL#</th>
                  <th>Name</th>
                  <th>Slug</th>
                  <th>Status</th>
                  <th>Created Date</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @php($i=0)
                @foreach (App\Models\Page:: all() as $page)
                <tr>
                  <td>{{++$i}}</td>
                  <td>{{$page->page_name}}</td>
                  <td>{{$page->slug}}</td>
                  <td class="status">
                    <label class="switch">
                      <input type="checkbox" onclick="catstatus({{$page->id}})" <?php if ($page->is_active == '1') {
                                                                                  echo "checked";
                                                                                } ?>>
                      <span class="slider"></span>
                    </label>
                  </td>
                  <td>{{date('d-M-Y',strtotime($page->created_at))}}</td>
                  <td class="center">
                    <a class="btn btn-success" href="#" data-toggle="modal" data-target="#view<?php echo $page['id'] ?>" title="View"><i class="fa fa-file-text" aria-hidden="true"></i></a>
                    <a class="btn btn-info" href="{{route('pages.edit',$page->id)}}" title="Edit"><i class="fa fa-edit"></i></a>
                    <a class="btn btn-danger" href="{{route('pages.destroy',$page->id)}}" onclick="return confirm('Are you sure to delete!');" title="Delete"> <i class="fa fa-remove"></i> </a>
                  </td>

                  <!-- modal starts -->
                  <div id="view<?php echo $page['id'] ?>" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                          <h4 class="modal-title" id="myModalLabel"><?php echo ucwords(stripslashes($page['page_name'])); ?></h4>
                        </div>
                        <div class="modal-body" style="padding:0px;">
                          <div class="col-md-12">
                            <div class="">
                              <!-- <div class="control-group">
                                      <label for="name" class="col-lg-3 control-label"><b>Name :</b></label>
                                      <div class="col-lg-9">
                                        <?php echo ucwords($page['page_name']); ?>
                                      </div>
                                    </div>
                                    <div class="clearfix"></div> -->
                              <div class="control-group">
                                <label for="slug" class="col-lg-3 control-label"><b>Slug :</b></label>
                                <div class="col-lg-9">
                                  <?php echo $page['slug']; ?>
                                </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="control-group">
                                <label for="description" class="col-lg-3 control-label"><b>Content Title :</b></label>
                                <div class="col-lg-9">
                                  <?php echo ucwords($page['content_title']); ?>
                                </div>
                              </div>


                              <div class="clearfix"></div>
                              <div class="control-group">
                                <label for="description" class="col-lg-3 control-label"><b>Description :</b></label>
                                <div class="col-lg-9">
                                  <?php echo ucwords($page['description']); ?>
                                </div>
                              </div>

                              <div class="clearfix"></div>
                              <div class="control-group">
                                <label for="description" class="col-lg-3 control-label"><b>Short Description :</b></label>
                                <div class="col-lg-9">
                                  <?php echo stripslashes(nl2br($page['description'])); ?>
                                </div>
                              </div>
                              <!-- <div class="clearfix"></div>
                                    <div class="control-group">
                                      <label for="image" class="col-lg-3 control-label"><b>Image :</b></label>
                                      <div class="col-lg-9">
                                      </div>
                                    </div>
                                    <div class="clearfix"></div>

                                    <div class="control-group">
                                      <label for="page_banner_text" class="col-lg-3 control-label"><b>Banner Text :</b></label>
                                      <div class="col-lg-9">
                                        <?php echo ucwords($page['page_banner_text']); ?>
                                      </div>
                                    </div> -->
                              <div class="clearfix"></div>
                              <div class="control-group">
                                <label for="meta_title" class="col-lg-3 control-label"><b>Meta Title :</b></label>
                                <div class="col-lg-9">
                                  <?php echo ucwords($page['meta_title']); ?>
                                </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="control-group">
                                <label for="meta_key" class="col-lg-3 control-label"><b>Meta Key :</b></label>
                                <div class="col-lg-9">
                                  <?php echo ucwords($page['meta_key']); ?>
                                </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="control-group">
                                <label for="meta_desc" class="col-lg-3 control-label"><b>Meta Description :</b></label>
                                <div class="col-lg-9">
                                  <?php echo ucwords($page['meta_desc']); ?>
                                </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="control-group">
                                <label for="status" class="col-lg-3 control-label"><b>Status :</b></label>
                                <div class="col-lg-9">
                                  <?php if ($page['is_active'] == 1) {
                                  ?>
                                    <span class="label label-success">Publish</span>
                                  <?php } else { ?>
                                    <span class="label label-warning">Unpublish</span>
                                  <?php } ?>
                                </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="control-group">
                                <label for="created" class="col-lg-3 control-label"><b>Created Date :</b></label>
                                <div class="col-lg-9">
                                  <?php echo date('d-m-Y', strtotime($page['created_at'])); ?>
                                </div>
                              </div>
                              <div class="clearfix"></div>
                              <div class="control-group">
                                <label for="modified" class="col-lg-3 control-label"><b>Modified Date :</b></label>
                                <div class="col-lg-9">
                                  <?php
                                  if (!$page['updated_at']) {
                                    echo "....";
                                  } else {

                                    echo date('d-m-Y', strtotime($page['updated_at']));
                                  }
                                  ?>
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
                  <th>Name</th>
                  <th>Slug</th>
                  <th>Status</th>
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

<script>
  function catstatus(id) {
    $.ajax({
      url: "{{ route('pages.status')}}",
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