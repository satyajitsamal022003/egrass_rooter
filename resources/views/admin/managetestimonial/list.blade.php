@extends('layouts.admin.layout')
@section('section')
    <!-- <body> -->
    <!-- Main content -->
    <div class="container-fluid">

        <!-- Row Starts -->
        <div class="row">
            <div class="col-sm-12 p-0">
                <div class="main-header">
                    <h4>Manage Testimonials</h4>
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
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="datatable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>SL#</th>
                                        <th>Client Name</th>
                                        <th>Image</th>
                                        <th>Designation</th>
                                        <th>Message</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php($i = 0)
                                    @foreach ($testimonials as $testimo)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $testimo->client_name }}</td>
                                            <td>
                                                <img class="img-fluid mt-2"
                                                    style="height: 151px;width: 153px;border-radius: 79px;"
                                                    src="{{ asset('images/testimonials/' . $testimo->client_image) }}"
                                                    alt="Preview">
                                            </td>
                                            <td>{{ $testimo->position }}</td>
                                            <td>{{ $testimo->description }}</td>
                                            <td class="status">
                                                <label class="switch">
                                                    <input type="checkbox" onclick="catstatus({{ $testimo->id }})"
                                                        <?php if ($testimo->is_active == '1') {
                                                            echo 'checked';
                                                        } ?>>
                                                    <span class="slider"></span>
                                                </label>
                                            </td>
                                            <td>{{ date('d-M-Y', strtotime($testimo->created_at)) }}</td>
                                            <td class="center">
                                                <a class="btn btn-info"
                                                    href="{{ route('managetestimonial.edit', $testimo->id) }}"
                                                    title="Edit"><i class="fa fa-edit"></i></a>
                                                <a class="btn btn-danger"
                                                    href="{{ route('managetestimonial.destroy', $testimo->id) }}"
                                                    onclick="return confirm('Are you sure to delete!');" title="Delete"><i
                                                        class="fa fa-remove"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>SL#</th>
                                        <th>Client Name</th>
                                        <th>Image</th>
                                        <th>Designation</th>
                                        <th>Message</th>
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
    <script>
        function catstatus(id) {
            $.ajax({
                url: "{{ route('managetestimonial.status') }}",
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
    <!-- </html> -->
@endsection
