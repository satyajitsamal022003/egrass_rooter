@extends('layouts.admin.layout')
@section('section')
    <!-- <body> -->
    <!-- Main content -->
    <div class="container-fluid">

        <!-- Row Starts -->
        <div class="row">
            <div class="col-sm-12 p-0">
                <div class="main-header">
                    <h4>Manage Blog</h4>
                    <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                    class="icofont icofont-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">Manage Blogs</a>
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
                        <form id="filter-form" method="get" class="form-horizontal">
                            <div class="col-md-3">
                                <select id="tag_id" name="tag_slug" data-rel="chosen"
                                    class="form-control col-md-7 col-xs-12">
                                    <option value="">--Select Tag--</option>
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->slug }}"
                                            {{ request('tag_slug') == $tag->slug ? 'selected' : '' }}>
                                            {{ $tag->tag_title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="cat_id" name="cat_slug" data-rel="chosen"
                                    class="form-control col-md-7 col-xs-12">
                                    <option value="">--Select Category--</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->slug }}"
                                            {{ request('cat_slug') == $category->slug ? 'selected' : '' }}>
                                            {{ $category->cat_title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <input type="search" name="searchtxt" id="searchtxt" placeholder=" Search by Title"
                                    value="{{ request('searchtxt') }}" class="form-control">
                            </div>
                            <div class="col-sm-1">
                                <button type="submit" id="filter-btn" class="btn btn-info search">Filter</button>
                            </div>
                            <div class="col-sm-1">
                                <button type="reset" id="reset-btn" class="btn btn-info search">Reset</button>
                            </div>
                        </form>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="datatable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>SL#</th>
                                        <th>Category</th>
                                        <th>Tag</th>
                                        <th>Title</th>
                                        <th>Blog Image</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 0)
                                    @foreach ($blogs as $blog)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $blog->cat_slug }}</td>
                                            <td>{{ $blog->tag_slug }}</td>
                                            <td>{{ $blog->page_name }}</td>
                                            <td>
                                                <img class="img-fluid mt-2" style="height: 100px;width: 165px;"
                                                    src="{{ asset('images/blogs/' . $blog->blog_image) }}" alt="Preview">
                                            </td>
                                            <td class="status">
                                                <label class="switch">
                                                    <input type="checkbox" onclick="catstatus({{ $blog->id }})"
                                                        <?php if ($blog->is_active == '1') {
                                                            echo 'checked';
                                                        } ?>>
                                                    <span class="slider"></span>
                                                </label>
                                            </td>
                                            <td>{{ date('d-M-Y', strtotime($blog->created_at)) }}</td>
                                            <td class="center">
                                                <a class="btn btn-info"
                                                    href="{{ route('manageblog.commentlist', $blog->id) }}"
                                                    title="list">Comments</a><br>
                                                <a class="btn btn-success" href="{{ route('manageblog.edit', $blog->id) }}"
                                                    title="Edit"><i class="fa fa-edit"></i></a>
                                                <a class="btn btn-danger"
                                                    href="{{ route('manageblog.destroy', $blog->id) }}"
                                                    onclick="return confirm('Are you sure to delete!');" title="Delete"><i
                                                        class="fa fa-remove"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>SL#</th>
                                        <th>Category</th>
                                        <th>Tag</th>
                                        <th>Title</th>
                                        <th>Blog Image</th>
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
            $('#reset-btn').click(function() {
                window.location.href = "{{ route('manageblog.list') }}";
            });
        });

        function catstatus(id) {
            $.ajax({
                url: "{{ route('manageblog.status') }}",
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

    <!-- filter -->
    {{-- <script>
        $(document).ready(function() {
            $('#filter-btn').click(function() {
                var formData = $('#filter-form').serialize();

                $.ajax({
                    url: "{{ route('manageblog.filter') }}",
type: 'GET',
data: formData,
success: function(response) {
$('#datatable').html(response);
},
error: function(xhr) {
console.log(xhr.responseText);
}
});
});

$('#reset-btn').click(function() {
$('#filter-form')[0].reset(); // Reset the form
$('#filter-btn').click(); // Trigger filter button click to reset the table content
});
});
</script> --}}
    <!-- </body> -->
    <!-- </html> -->
@endsection
