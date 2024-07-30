<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Election Result</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0/js/select2.min.js"></script>
</head>

<body>
    @extends('layouts.admin.layout')
    @section('section')
    <div class="container-fluid">
        <!-- Main content starts -->
        <div>
            <!-- Row Starts -->
            <div class="row">
                <div class="col-sm-12 p-0">
                    <div class="main-header">
                        <h4>Add Election Result</h4>
                        <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="icofont icofont-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('manageelectionresult.list') }}">Manage Election Result</a></li>
                            <li class="breadcrumb-item"><a>Add Election Result</a></li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- Row end -->

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-header-text">Add Election Result</h5>
                    </div>
                    <div class="card-block">
                        <form method="post" action="{{ route('manageelectionresult.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label for="pollingunit" class="col-xs-2 col-form-label form-control-label">Polling Units *</label>
                                <div class="col-sm-10">
                                    <select name="pollingunit" id="pollingunit" class="form-control">
                                        <option value="">--Select--</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="electionyear" class="col-xs-2 col-form-label form-control-label">Election Year *</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="electionyear" id="electionyear" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="votes" class="col-xs-2 col-form-label form-control-label">Votes *</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="votes" id="votes" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="party" class="col-xs-2 col-form-label form-control-label">Political Party *</label>
                                <div class="col-sm-10">
                                    <select name="party" id="party" class="form-control">
                                        <option value="">--Select--</option>
                                        @foreach (App\Models\Party::where('is_active', 1)->get() as $party)
                                        <option value="{{ $party->id }}">{{ $party->party_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary">Add Votes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Main content ends -->
    </div>
</body>

<script>
$(document).ready(function() {
    $('#pollingunit').select2({
        ajax: {
            url: "{{ route('electpollingunits') }}",
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    q: params.term // Query parameter
                };
            },
            processResults: function(data) {
                return {
                    results: data.map(function(item) {
                        return {
                            id: item.id,
                            text: item.polling_name
                        };
                    })
                };
            },
            cache: true
        },
        placeholder: 'Select a polling unit',
        minimumInputLength: 1
    });
});
</script>

</html>
@endsection
