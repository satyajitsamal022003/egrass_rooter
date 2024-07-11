<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votes Imports</title>
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


            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-header-text">Add Political Parties</h5>
                    </div>
                    <div class="card-block">
                        <form method="post" action="{{ route('manageparty.importparty') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">Upload CSV File<span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input name="upload_csv" id="upload_csv" type="file" class="form-control" required><br>
                                    <a href="{{asset('/partycsvfile/egrassrooter_party_import.csv')}}" download="">CSV Format?</a>
                                    <h6>Note*: Do not include the first row of title.</h6>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary">Import Party</button>
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