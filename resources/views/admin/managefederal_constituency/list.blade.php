@extends('layouts.admin.layout')
@section('section')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 p-0">
                <div class="main-header">
                    <h4>Manage Federal Constituency</h4>
                    <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
                        <li class="breadcrumb-item"><a href="index.html"><i class="icofont icofont-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="#">Forms Components</a></li>
                        <li class="breadcrumb-item"><a href="form-elements-bootstrap.html">General Elements</a></li>
                    </ol>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-12">
                    <div class="card" style="padding: 20px;">
                        <div class="card-body">
                            <table id="federaltable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>SL#</th>
                                        <th>State</th>
                                        <th>Federal Constituency Name</th>
                                        <th>Code</th>
                                        {{-- <th>Composition</th> --}}
                                        <th>Collation Center Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        $('#federaltable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('federalconst.getfederalconstituency') }}",
                data: function(d) {
                    d.searchtxt = $('#searchtxt').val();
                },
                type: 'GET'
            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'state_name',
                    name: 'state_name'
                },
                {
                    data: 'federal_name',
                    name: 'federal_name'
                },
                {
                    data: 'code',
                    name: 'code'
                },
                {
                    data: 'collation_center',
                    name: 'collation_center'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ],
        });
    });
</script>


@section('scripts')
@endsection
