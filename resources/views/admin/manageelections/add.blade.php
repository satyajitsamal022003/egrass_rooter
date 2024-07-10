<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Election Result</title>
</head>
@extends('layouts.admin.layout')
@section('section')

<body>
    <div class="container-fluid">
        <!-- Main content starts -->
        <div>
            <!-- Row Starts -->
            <div class="row">
                <div class="col-sm-12 p-0">
                    <div class="main-header">
                        <h4>General Elements</h4>
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


            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-header-text">Add Election Result</h5>
                    </div>
                    <div class="card-block">
                        <form method="post" action="{{ route('manageelection.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row">
                                <label for="polling_units" class="col-xs-2 col-form-label form-control-label">Polling
                                    Units</label>
                                <div class="col-sm-10">
                                    <select class="form-control col-md-7 col-xs-12 js-example-basic-single" id="polling_units" name="polling_units">
                                        <option value="">Select Polling Unit</option>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Election Year *</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="election_year" id="election_year" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Votes
                                    *</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="votes" id="votes" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Pollitical Party
                                    *</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="political_party" id="political_party" required>
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

</html>
<!-- Include jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Select2 JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/js/select2.min.js"></script>
<!-- CSS for Select2 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
<script>
    jQuery(document).ready(function($) {
        $('.js-example-basic-single').select2({
            allowClear: true,
            width: '100%',
            dropdownParent: $('#polling_units').parent(),
            ajax: {
                url: "{{ route('getPollingUnits') }}", // Replace with your Laravel route name
                dataType: 'json',
                delay: 250, // Delay in milliseconds before the request is sent
                processResults: function(data) {
                    console.log('Data received from server:', data); // Debugging line
                    return {
                        results: $.map(data, function(item) {
                            return {
                                id: item.id,
                                text: item.text
                            };
                        })
                    };
                },
                error: function(jqXHR, textStatus, errorThrown) { // Error handling
                    console.error('Error fetching data:', textStatus, errorThrown);
                },
                cache: true
            }
        });
    });
</script>
@endsection