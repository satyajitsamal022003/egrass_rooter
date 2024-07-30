<?php
if ($senatorial_zones->count() > 0) {
    $federal_total_local_constituency_count = 0;
    $federal_total_ward_count = 0;
    $federal_total_polling_unit_count = 0;
    $federal_total_polling_agent_count = 0;
    $total_federal_constituency_count = 0;

	//Fetch the count of Federal constituencies for each senatorial zone
	foreach($senatorial_zones as $lgahrc){
		$houseofrepresentative = FederalConstituency::find([
			 'conditions' => 'senatorial_state_id = :senatorial_state_id:',
			 'bind'       => ['senatorial_state_id' => $lgahrc->id]
			]);
			
	    $total_federal_constituency_count += count($houseofrepresentative);
			
	
    // Fetch the count of local constituencies for each senatorial zone
    foreach ($houseofrepresentative as $federal_zone) {
        $federal_local_constituencies = LocalConstituency::find([
            'conditions' => 'federal_constituency_id = :federal_id:',
            'bind'       => ['federal_id' => $federal_zone->id]
        ]);

        $federal_total_local_constituency_count += count($federal_local_constituencies);

        // Wards under the specific LGA selected
        foreach ($federal_local_constituencies as $federal_constituency) {
            $federal_wards = Ward::find([
                'conditions' => 'lga_id = :lga_id:',
                'bind'       => ['lga_id' => $federal_constituency->id]
            ]);

            $federal_total_ward_count += count($federal_wards);

            // Polling Units under the specific wards selected
            foreach ($federal_wards as $federal_ward) {
                $federal_polling_unit_count = PollingUnit::count([
                    'conditions' => 'ward_id = :ward_id:',
                    'bind'       => ['ward_id' => $federal_ward->id]
                ]);

                $federal_total_polling_unit_count += $federal_polling_unit_count;
                
             // Count Polling Agents under the specific polling units selected
                $federal_polling_agents = PollingAgent::count([
                    'conditions' => 'polling_units = :polling_unit_id:',
                    'bind'       => ['polling_unit_id' => $federal_polling_unit_count->id]
                ]);

                $federal_total_polling_agent_count += $federal_polling_agents;
            }
        }
    }
  }
}
 ?>