<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Election Result</title>
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
                        <h4>Edit Election Result</h4>
                        <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="icofont icofont-home"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="{{route('manageelectionresult.list')}}">Manage Election Result</a>
                            </li>
                            <li class="breadcrumb-item"><a>Edit Election Result</a>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            <!-- Row end -->

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-header-text">Edit Election Result</h5>
                    </div>
                    <div class="card-block">
                        <form method="post" action="{{ route('manageelectionresult.update', $editelectionresult->id) }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row">
                                <label for="election_type" class="col-xs-2 col-form-label form-control-label">Election Type *</label>
                                <div class="col-sm-10">
                                    <select id="election_type" name="election_type" class="form-control" required>
                                        <option value="">Select Election Type</option>
                                        <option value="1" {{ $editelectionresult->election_type == 1 ? 'selected' : '' }}>Presidential Election</option>
                                        <option value="2" {{ $editelectionresult->election_type == 2 ? 'selected' : '' }}>Senatorial Election</option>
                                        <option value="3" {{ $editelectionresult->election_type == 3 ? 'selected' : '' }}>House of Representative Election</option>
                                        <option value="4" {{ $editelectionresult->election_type == 4 ? 'selected' : '' }}>Governorship Election</option>
                                        <option value="5" {{ $editelectionresult->election_type == 5 ? 'selected' : '' }}>House of Assembly Election</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="state_id" class="col-xs-2 col-form-label form-control-label">State Name *</label>
                                <div class="col-sm-10">
                                    <select id="state_id" name="state_id" class="form-control" required>
                                        <option value="">Select State</option>
                                        @foreach (App\Models\State::all() as $s)
                                        <option value="{{ $s->id }}" {{ $editelectionresult->state_id == $s->id ? 'selected' : '' }}>{{ $s->state }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="party_id" class="col-xs-2 col-form-label form-control-label">Political Party *</label>
                                <div class="col-sm-10">
                                    <select id="party_id" name="party_id" class="form-control" required>
                                        <option value="">Select Political Party</option>
                                        @foreach (App\Models\Party::where('is_active', 1)->get() as $party)
                                        <option value="{{ $party->id }}" {{ $editelectionresult->party_id == $party->id ? 'selected' : '' }}>{{ $party->party_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="vote_value" class="col-xs-2 col-form-label form-control-label">Vote Value *</label>
                                <div class="col-sm-10">
                                    <input class="form-control" id="vote_value" name="vote_value" type="number" min="0" value="{{ $editelectionresult->vote_value }}" required>
                                    <label id="errpass" style="display:none;color:red;"></label>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="year_select" class="col-xs-2 col-form-label form-control-label">Election Year *</label>
                                <div class="col-sm-10">
                                    <select id="year_select" name="election_year" class="form-control" required>
                                        <!-- Years will be added here by JavaScript -->
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" name="admin_edit" id="admin_edit" class="btn btn-primary">Update</button>
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
    const startYear = 2003;
    const currentYear = new Date().getFullYear();
    const endYear = currentYear + 5;

    // Use PHP's json_encode to pass the election_year variable into JavaScript
    const selectedYear ={{ $editelectionresult->election_year }};
    

    const select = document.getElementById('year_select');

    for (let i = startYear; i <= endYear; i++) {
        const option = document.createElement('option');
        option.value = i;
        option.text = i;
        if (i === selectedYear) {
            option.selected = true; // Pre-select the year based on the PHP variable
        }
        select.appendChild(option);
    }
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Hide state and party by default
        $('#state-group').hide();

        $('#election_type').on('change', function() {
            var selectedValue = $(this).val();
            if (selectedValue == '1' || selectedValue == '') {
                $('#state-group').hide();
                $('#state_id').removeAttr('required');
            } else {
                $('#state-group').show();
                $('#state_id').attr('required', 'required');
            }
        });
    });
</script>

</html>
@endsection