@extends('layouts.admin.layout')
@section('section')
    <!-- <body> -->
    <!-- Main content -->
    <div class="container-fluid">

        <!-- Row Starts -->
        <div class="row">
            <div class="col-sm-12 p-0">
                <div class="main-header">
                    <h4>Manage CMS Pages</h4>
                    <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                    class="icofont icofont-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">Manage CMS Pages</a>
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
                        <div class="card-body">
                            <table id="datatable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Sl</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 0)
                                    @foreach ($cmsPages as $val)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $val->title ? $val->title : 'NA' }}</td>
                                            <td>{{ $val->status == 1 ? 'Publish' : 'Draft' }}</td>
                                            <td class="center">
                                                <a class="btn btn-success"
                                                    href="{{ route('cmspages.edit', $val->id) }}" title="Edit"><i
                                                        class="fa fa-edit"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
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
@endsection
