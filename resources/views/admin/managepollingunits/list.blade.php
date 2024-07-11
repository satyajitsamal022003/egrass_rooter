@extends('layouts.admin.layout')
@section('section')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12 p-0">
            <div class="main-header">
                <h4>Manage Polling Unit</h4>
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
                        <table id="pollingunitTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>SL#</th>
                                    <th>State</th>
                                    <th>LGA</th>
                                    <th>Ward</th>
                                    <th>Polling Name</th>
                                    <th>Polling Capacity</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
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
        $('#pollingunitTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('managepollings.getlist') }}",
                type: 'GET'
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'state_name', name: 'state_name' },
                { data: 'lga', name: 'lga' },
                { data: 'ward_id', name: 'ward_id' },
                { data: 'polling_name', name: 'polling_name' },
                { data: 'polling_capacity', name: 'polling_capacity' },
                { data: 'created_at', name: 'created_at' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false }
            ],
        });
    });
</script>


@section('scripts')


@endsection
