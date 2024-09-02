<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Upcoming election</title>
    <!-- Include Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
                        <h4>Add Upcoming election</h4>
                        <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="icofont icofont-home"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('upcomingelections')}}">Manage Upcoming elections</a>
                            </li>
                            <li class="breadcrumb-item"><a>Add Upcoming election</a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div> 
            <!-- Row end -->


            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-header-text">Add Upcoming election</h5>
                    </div>
                    <div class="card-block">
                        <form method="post" action="{{route('postupcomingelections')}}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row">
                                <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Type of Election*</label>
                                <div class="col-sm-10">
                                    <select name="election_type" id="election_type" class="form-control" required onchange="toggleStateField()">
                                        <option value="">Select Type</option>
                                        @foreach (App\Models\Electionresulttype::get(['id', 'type']) as $election)
                                        <option value="{{ $election->id }}">{{ $election->type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row" id="stateField">
                                <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">State Name*</label>
                                <div class="col-sm-10">
                                    <select name="state_id" id="state_id" class="form-control" required>
                                        <option value="">Select State</option>
                                        @foreach (App\Models\State::all(['id', 'state']) as $state)
                                        <option value="{{ $state->id }}">{{ $state->state }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="example-text-input" class="col-xs-2 col-form-label form-control-label">Date of Election*</label>
                                <div class="col-sm-10">
                                    <input class="form-control" type="text" name="election_date" id="election_date" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        <!-- Main content ends -->
    </div>
    <!-- Include Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        // Initialize Flatpickr on the input field
        flatpickr("#election_date", {
            dateFormat: "Y-m-d", // Set the date format to YYYY-MM-DD
            altInput: true, // Show an alternative input with a different date format
            altFormat: "F j, Y", // Alternative display format (e.g., "January 1, 2024")
            minDate: "today", // Disallow all past dates and start from today
        });

        // Function to toggle the visibility and required attribute of the state field
        function toggleStateField() {
            const electionType = document.getElementById('election_type').value;
            const stateField = document.getElementById('stateField');
            const stateSelect = document.getElementById('state_id');

            if (electionType === '1') {
                // Hide state field and remove required attribute
                stateField.style.display = 'none';
                stateSelect.removeAttribute('required');
                stateSelect.value = ''; // Clear the selected value if any
            } else {
                // Show state field and add required attribute
                stateField.style.display = 'flex';
                stateSelect.setAttribute('required', true);
            }
        }

        // Initial check on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleStateField();
        });
    </script>

</body>

</html>
@endsection