<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Campaigns</title>
</head>
@extends('layouts.admin.layout')
@section('section')

<body>
    <div class="container-fluid">
        <!-- Row Starts -->
        <div class="row">
            <div class="col-sm-12 p-0">
                <div class="main-header">
                    <h4>Campaign Manager List</h4>
                    <ol class="breadcrumb breadcrumb-title breadcrumb-arrow">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="icofont icofont-home"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{route('managecampaign.list')}}">Manage Campaign</a>
                        </li>
                        <li class="breadcrumb-item"><a href="form-elements-bootstrap.html">View</a>
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
                        <!-- <div class="card-header">
   <h3 class="card-title">Manage Pages/Content</h3>
 </div> -->
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table id="datatable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Campaign Name</th>
                                        <th>Campaign URL</th>
                                        <th>Campaign Type</th>
                                        <th>State</th>
                                        <th>Senatorial District</th>
                                        <th>Federal Constituency</th>
                                        <th>Local Constituency</th>
                                        <th>Political Party</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td class="center">
                                            @if(isset($campaignnext))
                                            @if($campaignnext->campaign_type == 1)
                                            President
                                            @elseif($campaignnext->campaign_type == 2)
                                            Senate
                                            @elseif($campaignnext->campaign_type == 3)
                                            House of Representative
                                            @elseif($campaignnext->campaign_type == 4)
                                            Governor
                                            @elseif($campaignnext->campaign_type == 5)
                                            House of Assembly
                                            @elseif($campaignnext->campaign_type == 6)
                                            Local Government Chairman
                                            @else
                                            Unknown
                                            @endif
                                            @else
                                            N/A
                                            @endif
                                        </td>

                                        <td class="center">
                                            @if($campaignnext && $campaignnext->state)
                                            @php($getAllState = \App\Models\State::find($campaignnext->state))
                                            @if($getAllState && $getAllState->state != "")
                                            {{ $getAllState->state }}
                                            @else
                                            N/A
                                            @endif
                                            @else
                                            N/A
                                            @endif
                                        </td>
                                        <td class="center">
                                            @if($campaignnext && $campaignnext->senatorial_district_id)
                                            @php($senatorialDistrict = \App\Models\Senatorial_state::find($campaignnext->senatorial_district_id))
                                            @if($senatorialDistrict && $senatorialDistrict->sena_district != "")
                                            {{ $senatorialDistrict->sena_district }}
                                            @else
                                            N/A
                                            @endif
                                            @else
                                            N/A
                                            @endif
                                        </td>

                                        <td class="center">
                                            @if($campaignnext && $campaignnext->federal_constituency_id)
                                            @php($federalConstituency = \App\Models\Federal_constituency::find($campaignnext->federal_constituency_id))
                                            @if($federalConstituency && $federalConstituency->federal_name != "")
                                            {{ $federalConstituency->federal_name }}
                                            @else
                                            N/A
                                            @endif
                                            @else
                                            N/A
                                            @endif
                                        </td>

                                        <td class="center">
                                            @if($campaignnext && $campaignnext->local_constituency_id)
                                            @php($localConstituency = \App\Models\Local_constituency::find($campaignnext->local_constituency_id))
                                            @if($localConstituency && $localConstituency->lga != "")
                                            {{ $localConstituency->lga }}
                                            @else
                                            N/A
                                            @endif
                                            @else
                                            N/A
                                            @endif
                                        </td>

                                        <td class="center">
                                            @if($campaignnext && isset($campaignnext->political_party))
                                            @php($party = \App\Models\Party::find($campaignnext->political_party))
                                            @if($party && $party->party_name != "")
                                            {{ $party->party_name }}
                                            @else
                                            N/A
                                            @endif
                                            @else
                                            N/A
                                            @endif
                                        </td>

                                        <!-- <td></td>
       <td></td>
       <td></td> -->
                                    </tr>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Campaign Name</th>
                                        <th>Campaign URL</th>
                                        <th>Campaign Type</th>
                                        <th>State</th>
                                        <th>Senatorial District</th>
                                        <th>Federal Constituency</th>
                                        <th>Local Constituency</th>
                                        <th>Political Party</th>
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
</body>

</html>
@endsection