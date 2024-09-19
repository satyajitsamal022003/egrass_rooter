<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Upcomingelection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UpcomingElectionController extends Controller
{
    public function upcomingelection(Request $request)
    {
        // Get today's date
        $today = Carbon::now()->startOfDay();

        // Get the closest upcoming election date
        $closestDate = Upcomingelection::where('is_active', 1)->where('election_date', '>=', $today)
            ->min('election_date');

        if (!$closestDate) {
            return response()->json([
                'status' => 'success',
                'message' => 'No Upcoming Elections Found',
                'data' => []
            ]);
        }

        $closestDate = Carbon::parse($closestDate);

        // Get elections for the closest date
        $elections = Upcomingelection::with('electionType', 'state')
            ->where('is_active', 1)
            ->whereDate('election_date', $closestDate->format('Y-m-d'))
            ->get();

        // Format the data and group by election type
        $groupedElections = $elections->groupBy(function ($election) {
            return $election->electionType->type ?? "";
        })->map(function ($electionsByType) {
            return $electionsByType->map(function ($election) {
                return $election->state->state ?? "";
            })->unique();
        });

        // Format the final response data
        $formattedData = [];
        foreach ($groupedElections as $type => $states) {
            $formattedData[$closestDate->format('Y-m-d')][] = [
                'election_type' => $type,
                'states' => $states
            ];
        }

        // Return response
        return response()->json([
            'status' => 'success',
            'message' => 'Upcoming Elections Retrieved Successfully',
            'data' => $formattedData
        ]);
    }
}
