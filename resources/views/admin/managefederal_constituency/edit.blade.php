<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Federal Constituency</title>
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
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="icofont icofont-home"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('federalconst.list')}}">Manage Federal Constituency</a>
                            </li>
                            <li class="breadcrumb-item"><a>Edit Federal Constituency</a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- Row end -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-header-text">Edit Federal Constituency</h5>
                    </div>
                    <div class="card-block">
                        <form method="post" action="{{route('federalconst.update',$editfederal->id)}}">
                            @csrf
                            <div class="form-group row">
                                <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">State</label>
                                <div class="col-sm-10">
                                    <select name="state" id="state" class="form-control">
                                        <option value="">Select State</option>
                                        @foreach (App\Models\State::all() as $statedata )
                                        <option value="{{$statedata->id }}" {{$statedata->id==$editfederal->state_id?'selected':''}}>{{$statedata->state }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Federal Constituency
                                    Name*</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="constituency" id="constituency" value="{{$editfederal->federal_name }}" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Code *</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="code" id="code" value="{{$editfederal->code }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Composition*</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="composition" id="composition" value="{{$editfederal->composition }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="example-text-input" class="col-xs-4 col-form-label form-control-label">Collation Center*</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="collation_centre" id="collation_centre" value="{{$editfederal->collation_center }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary">Update</button>
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
@endsection