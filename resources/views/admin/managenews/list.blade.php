@extends('layouts.admin.layout')
@section('section')
<!-- <body> -->
<!-- Main content -->
<div class="container-fluid">

    <!-- Row Starts -->
    <div class="row">
        <div class="col-sm-12 p-0">
            <div class="main-header">
                <h4>Manage News</h4>
                <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="icofont icofont-home"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Manage News</a>
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
                        <table id="lgatable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>SL#</th>
                                    <th>News Category</th>
                                    <th>News Title</th>
                                    <th>News Image</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i=0)
                                @foreach (App\Models\News_Social::all() as $newssoclist)
                                @php($newscat = App\Models\News_category::where('id',$newssoclist->newscategory)->first())
                                <tr>
                                    <td>{{++$i}}</td>
                                    <td>{{$newscat->name ?? ''}}</td>
                                    <td>{{$newssoclist->title ?? ''}}</td>
                                    <td>
                                    <img class="img-fluid mt-2" style="height: 112px;width: 199px;border-radius: 7px;" src="{{ asset('images/news/' . $newssoclist->image) }}" alt="Preview">
                                    </td>
                                    <td class="status">
                                        <label class="switch">
                                            <input type="checkbox" onclick="catstatus({{$newssoclist->id}})" <?php if ($newssoclist->status == '1') {
                                                echo "checked";
                                            } ?>>
                                            <span class="slider"></span>
                                        </label>
                                    </td>
                                    <td>{{date('d-M-Y',strtotime($newssoclist->created_at))}}</td>
                                    <td class="center">
                                        <a class="btn btn-info" href="{{route('edit-news',$newssoclist->id)}}" title="Edit"><i class="fa fa-edit"></i></a>
                                        <a class="btn btn-danger" href="{{route('delete-news',$newssoclist->id)}}" onclick="return confirm('Are you sure to delete!');" title="Delete"><i class="fa fa-remove"></i></a>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>SL#</th>
                                    <th>News Category</th>
                                    <th>News Title</th>
                                    <th>News Image</th>
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
<style>
    .me-3 {
        margin-right: 15px;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterButton = document.getElementById('filter-btn');
        const resetButton = document.getElementById('reset-btn');
        const searchInput = document.getElementById('searchtxt');

        filterButton.addEventListener('click', function() {
            const searchTerm = searchInput.value;

            $.ajax({
                url: "{{ route('managelga.list') }}",
                method: "GET",
                data: {
                    searchtxt: searchTerm
                },
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
                url: "{{ route('managelga.list') }}",
                method: "GET",
                data: {
                    searchtxt: ''
                },
                success: function(response) {
                    $('#party-list').html(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script> -->
<script>
    $(document).ready(function() {
        $('#lgatable').DataTable({
            // searching: false, // Disable the search bar
            lengthChange: false
        });
    });
</script>

<script>
  function catstatus(id) {
    $.ajax({
      url: "{{ route('news.status')}}",
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