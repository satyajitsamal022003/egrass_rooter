<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ElectionType;
use App\Models\Local_constituency;
use App\Models\Polling_unit;
use App\Models\State;
use App\Models\Ward;

class ElectionCampaignController extends Controller
{
    public function electioncampaignlist()
    {
        $electioncampaignlist = ElectionType::get();

        $electioncampaigndata = [];
        foreach ($electioncampaignlist as $edata) {
            $electioncampaigndata[] = [
                'id' => $edata->id,
                'campaign_type' => $edata->type,
            ];
        }

        return response()->json([
            'campaigntypedata' => $electioncampaigndata
        ]);
    }


    public function electioncampaignstatewise($campaigntype)
    {
        $statelist = State::where('election_type', $campaigntype)->get();

        $campaignwisestate = [];
        foreach ($statelist as $edata) {
            $campaignwisestate[] = [
                'id' => $edata->id,
                'state' => $edata->state,
            ];
        }

        $campaigndata = ElectionType::where('id', $campaigntype)->first();

        $electioncampaigndata = [
            'id' => $campaigndata->id,
            'election_campaign' => $campaigndata->type,
        ];

        return response()->json([
            'campaignwisestate' => $campaignwisestate,
            'electioncampaign' => $electioncampaigndata
        ]);
    }

    public function statewiselga($stateid)
    {
        $lgalist = Local_constituency::where('state_id', $stateid)
            ->with(['wards' => function ($query) {
                $query->withCount('pollingUnits');
            }])
            ->withCount('wards')
            ->get()
            ->map(function ($edata) {
                $totalPollingUnits = $edata->wards->sum('polling_units_count');

                return [
                    'id' => $edata->id,
                    'lga_name' => $edata->lga,
                    'wards_count' => $edata->wards_count,
                    'polling_units_count' => $totalPollingUnits,
                ];
            });

        $statedata = State::where('id', $stateid)->first();

        $electiontype = '';
        if ($statedata->election_type) {
            $campaigndata = ElectionType::where('id', $statedata->election_type)->first();
            $electiontype = $campaigndata->election_type;
        }

        $electionstatedata = [
            'id' => $statedata->id,
            'state_name' => $statedata->state
        ];

        $lgacount = count($lgalist);

        return response()->json([
            'lgawisestate' => $lgalist,
            'electionstatedata' => $electionstatedata,
            'lgacount' => $lgacount,
            'electiontype' => $electiontype
        ]);
    }

    public function lgawiseward($lgaid)
    {
        $lgawiseward = Ward::where('lga_id', $lgaid)
            ->withCount('pollingUnits')
            ->get()
            ->map(function ($edata) {
                return [
                    'id' => $edata->id,
                    'ward_name' => $edata->ward_details,
                    'polling_units_count' => $edata->polling_units_count,
                ];
            });

        $lgadata = Local_constituency::where('id', $lgaid)->first();

        $electionlgadata = [
            'id' => $lgadata->id,
            'lga_name' => $lgadata->lga,
        ];

        $statedata = State::where('id', $lgadata->state_id)->first();

        $electiontype = '';
        if ($statedata->election_type) {
            $campaigndata = ElectionType::where('id', $statedata->election_type)->first();
            $electiontype = $campaigndata->election_type;
        }

        $electionstatedata = [
            'id' => $statedata->id,
            'state_name' => $statedata->state
        ];

        return response()->json([
            'lgawiseward' => $lgawiseward,
            'electionlgadata' => $electionlgadata,
            'electionstatedata' => $electionstatedata,
            'electiontype' => $electiontype
        ]);
    }


    public function wardwisepu($wardid)
    {
        $wardwisepu = Polling_unit::where('ward_id', $wardid)->get()->map(function ($edata) {
            return [
                'id' => $edata->id,
                'polling_unit_name' => $edata->polling_name,
                'pu_code' => $edata->Delims,
            ];
        });

        $warddata = Ward::where('id', $wardid)->first();
        $lgadata = Local_constituency::where('id', $warddata->lga_id)->first();
        $electionlgadata = [
            'id' => $lgadata->id,
            'lga_name' => $lgadata->lga,
        ];

        $statedata = State::where('id', $lgadata->state_id)->first();

        $electiontype = '';
        if ($statedata->election_type) {
            $campaigndata = ElectionType::where('id', $statedata->election_type)->first();
            $electiontype = $campaigndata->election_type;
        }


        $polling_unit_count = Polling_unit::where('ward_id', $wardid)->get();

        $electionwarddata = [
            'id' => $warddata->id,
            'ward_name' => $warddata->ward_details,
        ];

        return response()->json([
            'wardwisepu' => $wardwisepu,
            'electionwarddata' => $electionwarddata,
            'lgadata' => $electionlgadata,
            'electiontype' => $electiontype,
            'polling_unit_count' => count($polling_unit_count)
        ]);
    }
}
