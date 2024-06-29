<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Election Result</title>
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
                        <h4>General Elements</h4>
                        <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
                            <li class="breadcrumb-item"><a href="index.html"><i class="icofont icofont-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="#">Forms Components</a></li>
                            <li class="breadcrumb-item"><a href="form-elements-bootstrap.html">General Elements</a></li>
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
                                        @foreach (App\Models\Polling_unit::all() as $polling)
                                            <option value="{{ $polling->id }}">{{ $polling->polling_name }}</option>
                                        @endforeach
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
</html>
@endsection
