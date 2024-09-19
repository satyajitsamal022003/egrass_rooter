<!-- khuhsi/ -->

<?php

use Phalcon\Tag as Tag;

UIFront::getHeader('dashboard');
$siteDetail = UIElements::BapCustUnisiteDetail(1);
$userID = $_SESSION['userid'];
UIFront::frontTopBar($userID);


$leadgrass = AddMember::find(array("conditions" => "role_type=1 and is_active=1 and user_id='" . $userID . "'", "order" => "id desc"));
$grassrooters = AddMember::find(array("conditions" => "role_type=2 and is_active=1 and user_id='" . $userID . "'", "order" => "id desc"));
//new code

$pollingagents = PollingAgent::find(array("conditions" => "user_id='" . $userID . "'", "order" => "id desc"));

//new code
//$pollingagents = AddMember::find(array("conditions" => "role_type=3 and is_active=1 and user_id='" . $userID . "'", "order" => "id desc"));
$campain_details = CampaignNext::findFirst(["user_id=" . $userID]);
$pollingunit = PollingUnit::find(array("conditions" => "state_id='" . $campain_details->state . "'", "order" => "id desc"));
$lgaCount = LocalConstituency::find(array("conditions" => "state_id='" . $campain_details->state . "'", "order" => "id desc"));
$contactNumber = Users::find(array("conditions" => "user_id='" . $userID . "'", "order" => "id desc"));
// echo $campain_details->state;exit;
$issues = Issue::find(array("conditions" => "", "order" => "id desc", "limit" => "3"));
$votesimportTbl = new VotesImport();
$sqlGetvotes = "SELECT SUM( votes ) as sumvotes FROM votes_import";
$getAllvotes = $votesimportTbl->findByAllCustom($sqlGetvotes);
$votes = $getAllvotes;
$donateTbl = new Donate();
$sqlGetdonate = "SELECT SUM( amount ) as sumamount FROM donate";
$getAlldonate = $donateTbl->findByAllCustom($sqlGetdonate);
$amount = $getAlldonate;
?>

<div class="breadcomb-area">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="breadcomb-list-dashborad">
					<div class="row">
						<div class="col-lg-2 col-xs-12 party-left">
							<?php if ($partyDetails->party_img) { ?>
								<span><img src="<?= BASEURL ?>assets/dashboard/party/<?php echo $partyDetails->party_img; ?> " alt="Party Logo"></span>
							<?php } else { ?>
								<span><img src="<?= BASEURL ?>assets/dashboard/party/default_party.png" alt="Default Logo"></span>
							<?php } ?>
						</div>
						<div class="col-lg-8 col-xs-12 party-mid">
							<div class="breadcomb-wp">
								<div class="breadcomb-ctn">
									<h2>Welcome to EgrassRooter</h2>
									<p></p>

									<p id="demo" style="margin-bottom: 4px;">
										<?php
										if (!empty($campainDetails)) {

											if ($campainDetails->campaign_type == 1) {
												echo "Campaign Name : Presidential Election Campaign";
											}
											if ($campainDetails->campaign_type == 2) {
												echo "Campaign Name : Senate Election Campaign";
											}
											if ($campainDetails->campaign_type == 3) {
												echo "Campaign Name : House of Representative Campaign";
											}
											if ($campainDetails->campaign_type == 4) {
												echo "Campaign Name : Governer Election Campaign";
											}
											if ($campainDetails->campaign_type == 5) {
												echo "Campaign Name : House of Assembly Campaign";
											}
											if ($campainDetails->campaign_type == 6) {
												echo "Campaign Name : Local Government Chairmen Campaign";
											}
											if ($campainDetails->campaign_type == 7) {
												echo "Campaign Name : Councilor Election Campaign";
											}
										} else {
											echo "Campaign Name : N/A";
										}
										?>
									</p>
									<!-- added by Santosh -->
									<p style="margin-bottom: 4px;">
										<?php
										if (!empty($userData))
											echo "Campaign Manager : " . $userData->first_name. ' ' .$userData->last_name;

										else
											echo "Campaign Manager : N/A";
										?>
									</p>
									<!-- added by Santosh -->
									<p style="margin-bottom: 4px;">
										<?php
										if (!empty($partyDetails))
											echo "For The Party : " . $partyDetails->party_name;

										else
											echo "For The Party : N/A";

										?>
									</p>
									<!-- commented by Santosh -->
									<!-- <p>
										<?php
										if (!empty($partyDetails))
											echo "Party Candidate : " . $partyDetails->owner_name;

										else
											echo "Party Candidate : N/A";
										?>
									</p> -->
								</div>
							</div>
						</div>
						<!-- commented by Santosh -->
						<!-- <div class="col-lg-2 col-xs-12 party-right">
							<?php if ($partyDetails->party_img) { ?>
								<span><img src="<?= BASEURL ?>assets/dashboard/party/<?php echo $partyDetails->candidate_img; ?> " alt="Party Candidate Photo"></span>
							<?php } else { ?>
								<span><img src="<?= BASEURL ?>assets/dashboard/party/default_candidate.png" alt="default Profile photo"></span>
							<?php } ?>
						</div> -->
						<!-- commented by Santosh -->
						
						<!-- added by Santosh -->
						<div class="col-lg-2 col-xs-12 party-right">
							<?php if ($userData->profile_image) { ?>
								<span><img src="<?= BASEURL ?>uploads/authprofile/<?php echo $userData->profile_image; ?> " alt="Campaign Manager Photo"></span>
							<?php } else { ?>
								<span><img src="<?= BASEURL ?>assets/dashboard/party/default_candidate.png" alt="default Profile photo"></span>
							<?php } ?>
						</div>
						<!-- added by Santosh -->

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Breadcomb area End-->
<?php
UIFront::frontTopMenu($userID);
?>

<?php
	$electionName = ElectionTypes::findFirst(array("conditions" => "id='" . $campaignElection->election_name . "'"));

	$currentDate = date('Y-m-d');
	$dateString  = $campaignElection->election_date;
	$date = new DateTime($dateString);
	$formattedDate = $date->format('jS F Y');
?>

<?php if ($electionusers) { ?>
	<?php if ($currentDate < $campaignElection->election_date) { ?>
	<!-- countdown clock code by narendra -->
	<div class="counter-box">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<p id="Headline">Next Election: <?php echo $electionName->type; ?> <?php echo $formattedDate ?></p>
					<!--<div class="counter-number-box">-->
					<!--    <p style=" text-align: center;font-size: 60px;margin-top: 0px;"id="demo"></p>-->
					<!--</div>-->
				</div>
			</div>
		</div>
	</div>


	<div class="countdown-box-sec">
		<div class="container">
			<div class="row">
				<div class="col-lg-12" style="width:100%;">
					<div id="countdown">
						<ul>
							<li><span id="days"></span>days</li>
							<li><span id="hours"></span>Hours</li>
							<li><span id="minutes"></span>Minutes</li>
							<li><span id="seconds"></span>Seconds</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } else { ?>

<div class="counter-box">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<p id="Headline">Next Election: No Election found</p>
			</div>
		</div>
	</div>
</div>

<?php } ?>

<?php } else { ?>

	<div class="counter-box">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<p id="Headline">Next Election: No Election found</p>
				</div>
			</div>
		</div>
	</div>

<?php } ?>


<!-- Start tabs area-->
<div class="tabs-info-area">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="widget-tabs-int">
					<div class="row">
						<div class="col-md-12 m-5" style="width: 100%;margin-bottom:15px">
							<div class="acme-news-ticker">
								<div class="acme-news-ticker-label">News </div>

								<div class="acme-news-ticker-box">
									<ul class="my-news-ticker">
										<?php foreach ($totrecordofnews as $allnews) { ?>
											<li><a href="#"><?= $allnews->title ?></a></li>
										<?php } ?>

									</ul>

								</div>
								<div class="acme-news-ticker-controls acme-news-ticker-horizontal-controls">
									<button class="acme-news-ticker-arrow acme-news-ticker-prev"></button>
									<button class="acme-news-ticker-pause"></button>
									<button class="acme-news-ticker-arrow acme-news-ticker-next"></button>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="leftside_activies">
							 	<div class="row">
									<div class="col-sm-6 col-lg-4 mb-2">
										<a href="<?= BASEURL ?>dashboard/grassrooter">
											<div class="leftside_activiescounterbox">

												<span><img src="https://egrassrooter.com/assets/dashboard/icon1.png" alt="icon 1"></span>
												<p>Grassrooters</p><span class="count">
													<?= count($grassrooters) ?>
												</span>
											</div>
										</a>
									</div>
									<?php /** 
									<div class="col-sm-6 col-lg-4 mb-2">
										<a href="<?= BASEURL ?>dashboard/leadgrassrooter">
											<div class="leftside_activiescounterbox">

												<span><img src="https://egrassrooter.com/assets/dashboard/icon8.png" alt="icon 1"></span>
												<p>Lead Grassrooters</p><span class="count">
													<?= count($leadgrass) ?>
												</span>
											</div>
										</a>
									</div>
									***/?>
									<div class="col-sm-6 col-lg-4 mb-2">
										<a href="<?= BASEURL ?>dashboard/pollingagentlist">
											<div class="leftside_activiescounterbox"><span><img src="https://egrassrooter.com/assets/dashboard/icon3.png" alt="icon 1"></span>
												<p>Polling Agent</p><span class="count">
													<?= count($pollingagents) ?>
												</span>
											</div>
										</a>
									</div>
									 <!-- <div class="col-sm-6 col-lg-4 mb-2">
										<a href="<?= BASEURL ?>dashboard/donation">
											<div class="leftside_activiescounterbox">
												<span><img src="https://egrassrooter.com/assets/dashboard/icon7.png" alt="icon 1"></span>
												<p>Donation</p><span class="count">$
													<?= $amount[0]->sumamount ?>
												</span>
											</div>
										</a>
									</div>  -->

									<!-- <div class="col-sm-6 col-lg-4 mb-2">
										<a href="<?= BASEURL ?>dashboard/numberofvotes">
											<div class="leftside_activiescounterbox">
												<span><img src="https://egrassrooter.com/assets/dashboard/icon4.png" alt="icon 1"></span>
												<p>Number of Contacts</p>
												<span class="count">
													<?= $votes[0]->sumvotes ?>
												</span>
											</div>
										</a>
									</div> -->

									<div class="col-sm-6 col-lg-4 mb-2">
										<a href="<?= BASEURL ?>contact">
											<div class="leftside_activiescounterbox">
												<span><img src="https://egrassrooter.com/assets/dashboard/icon4.png" alt="icon 1"></span>
												<p>Number of Contacts</p>
												<!-- votes to Contact -->
												<span class="count">
													<?= count($contactNumber) ?>
												</span>
											</div>
										</a>
									</div>

									<div class="col-sm-6 col-lg-4 mb-2">
										<a href="<?= BASEURL ?>dashboard/pollingunit">
											<div class="leftside_activiescounterbox">

												<span><img src="https://egrassrooter.com/assets/dashboard/icon2.png" alt="icon 1"></span>
												<p>Number Of PU</p><span class="count">
													<?= count($pollingunit) ?>
												</span>
											</div>
										</a>
									</div> 

									<div class="col-sm-6 col-lg-4 mb-2">
										<a href="<?= BASEURL ?>dashboard/pollingunit">
											<div class="leftside_activiescounterbox">

												<span><img src="https://egrassrooter.com/assets/dashboard/icon2.png" alt="icon 1"></span>
												<p>LGA</p><span class="count">
													<?= count($lgaCount) ?>
												</span>
												<!-- <p>Zones</p><span class="count">
													6
												</span> -->
											</div>
										</a>
									</div> 
									<!-- <div class="col-sm-6 col-lg-4 mb-2">
										<div class="leftside_activiescounterbox">
											<span><img src="https://egrassrooter.com/assets/dashboard/icon5.png"
													alt="icon 1"></span>
											<p>Up Vote</p><span class="count">70</span>
										</div>
									</div>
									<div class="col-sm-6 col-lg-4 mb-2">
										<div class="leftside_activiescounterbox">
											<img src="https://egrassrooter.com/assets/dashboard/icon6.png"
												alt="icon 1">
											<p>Engage Down Vote</p><span class="count">170</span>
										</div>
									</div>
									<div class="col-sm-6 col-lg-4 mb-2">
										<div class="leftside_activiescounterbox">
											<img src="https://egrassrooter.com/assets/dashboard/icon9.png"
												alt="icon 1">
											<p>Number Of Engaged Voters</p><span class="count">110</span>
										</div>
									</div>  -->
								 </div> 


							</div>
						</div>

						<div class="col-md-6">
							<div class="rightside_activies">
								<ul class="nav nav-tabs" id="myTab" role="tablist">
									<li class="nav-item active">
										<a class="nav-link" id="event-tab" data-toggle="tab" href="#event" role="tab" aria-controls="event" aria-selected="true">Future Events</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" id="past-event-tab" data-toggle="tab" href="#past-event" role="tab" aria-controls="past-event" aria-selected="false">Past Events</a>
									</li>
								</ul>

								<div class="tab-content" id="myevent-tab">
									<div class="tab-pane fade active in" id="event" role="tabpanel" aria-labelledby="event-tab">
										<div class="event-box scroll-auto-news" id="future" style="height: 500px; overflow: scroll;">
											<ul>
												<?php
												if (count($FutureEvents) == 0) { ?>

													<h4 class="center" style="margin:35%">No events found</h4>

												<?php }
												?>
												<?php
												foreach ($FutureEvents as $Fevents) {
												?>
													<li>

														<a href="<?php echo BASEURL; ?>broadcast/eventdetails/<?php echo $Fevents->id; ?>" class="event-cl-box-link">
															<div class="event-cl-box">
																<div class="event-date-box">
																	<div class="event-date-month">
																		<span><?= strtoupper(date("M", strtotime($Fevents->event_date))) ?></span>
																	</div>
																	<div class="event-date">
																		<span><?= date("d", strtotime($Fevents->event_date)) ?></span>
																	</div>
																</div>
																<div class="event-desc-box">
																	<h4><?= $Fevents->title ?></h4>
																	<div class="event-v-da">
																		<div class="event-venue">
																			<h6>Venue <span><?= $Fevents->address ?></span></h6>

																		</div>
																		<div class="event-cl">
																			<span><i class="fa fa-clock-o" aria-hidden="true"></i><?= date('h:i A', strtotime($Fevents->event_time)); ?></span>
																		</div>
																	</div>
																	<p><?= $Fevents->description ?>. </p>
																</div>
															</div>
														</a>



													</li>
												<?php }


												?>

											</ul>
										</div>
									</div>



									<div class="tab-pane fade" id="past-event" role="tabpanel" aria-labelledby="past-event-tab">
										<div class="event-box scroll-auto-news" id="past">
											<ul>
												<?php foreach ($PreviousEvents as $Pevents) {
												?>
													<li>
													<a href="<?php echo BASEURL; ?>broadcast/eventdetails/<?php echo $Pevents->id; ?>" class="event-cl-box-link">
															<div class="event-cl-box">
																<div class="event-date-box">
																	<div class="event-date-month">
																		<span><?= strtoupper(date("M", strtotime($Pevents->event_date))) ?></span>
																	</div>
																	<div class="event-date">
																		<span><?= date("d", strtotime($Pevents->event_date)) ?></span>
																	</div>
																</div>
																<div class="event-desc-box">
																	<h4><?= $Pevents->title ?></h4>
																	<div class="event-v-da">
																		<div class="event-venue">
																			<h6>Venue <span><?= $Pevents->address ?></span></h6>

																		</div>
																		<div class="event-cl">
																			<span><i class="fa fa-clock-o" aria-hidden="true"></i><?= date('h:i A', strtotime($Pevents->event_time)); ?></span>
																		</div>
																	</div>
																	<p><?= $Pevents->description ?>. </p>
																</div>
															</div>
														</a>
													</li>
												<?php }

												?>
											</ul>
										</div>


									</div>
								</div>
							</div>
						</div>


						<?php
						$sqlelectron = "SELECT * FROM PartyVote GROUP BY election_year";
						$getelection = $this->modelsManager->executeQuery($sqlelectron);
						$allyear = $getelection;

						?>

						<!-- commented by khushi -->
						<!-- <div class="col-md-6">
							<h5>Latest Polling Unit Result</h5>
							<div style="float:right;" class="clearfix">
								<!-- <select onchange="getYearValue(this.value);">
									<option value="">Select Year</option>
									<?php //foreach ($allyear as $getyear) {

									?>
										<option value="<? php // echo  $getyear->election_year 
														?>"><?php //echo  $getyear->election_year 
															?></option>
									<?php //} 
									?>
								</select>  -->
							<!-- </div>

							<?php


							// $partyvotes = "SELECT party_vote.state_id, party_vote.party_id, SUM(party_vote.vote_value) as vote_value,party_vote.election_year FROM PartyVote as party_vote  WHERE  party_vote.election_year = 2019 GROUP BY party_vote.state_id";
							// $getvotes = $this->modelsManager->executeQuery($partyvotes); 

							// $statearr = array();
							// $vote_value = array();
							// if ($getvotes) {
							// 	foreach ($getvotes as $value) {
							// 		$state = States::findFirst($value->state_id);
							// 		$statearr[] = $state->state;
							// 		$vote_value[] = $value->vote_value;
							// 	}

								//return $this->response->setJsonContent(["vote_value" => $vote_value, "statearr" => $statearr]);
							// } else {

								//return $this->response->setJsonContent(["vote_value" => $vote_value, "statearr" => $statearr]);
							// }
							// ?>
							<canvas id="myChart"></canvas> -->
						<!-- </div> --> 
<!-- commented by khushi -->
						<!-- previous -->

						<div class="col-md-6">
							<h5>YearWise Election Result</h5>
							<div style="float:right;" class="clearfixX">
								<select onchange="grade_Change(this.value);">
									<option >Select Year</option>
									<?php foreach ($allyear as $getyear) {

									?>
										<option name="election_year" id="election_year" value="<?php echo  $getyear->election_year?>"><?php echo  $getyear->election_year
															?></option>
									<?php }
									?>
								</select>
							</div>

							<!-- <div style="float:right;" class="clearfixX">
								<select onchange="change_party(this.value);">
									<option value="">Select party</option>
									<?php foreach ($allparty as $party) {

									?>
										<option name="party_name" id="party_name" value="<?php echo  $party->id?>"><?php echo  $party->party_name
															?></option>
									<?php }
									?>
								</select>
							</div> -->

                            <div id="hide"> <h3 style="margin-top: 20%; margin-left: 30%;">Select year..</h3></div>
							<canvas id="myChart1"></canvas>
						</div>
					</div>

				</div>
			</div>
		</div>

	</div>
</div>
<!-- Start tabs area-->



<?php
UIFront::getDashboradFooter('dashboard');
?>
<script>
	$(document).ready(function($) {
		$('.my-news-ticker').AcmeTicker({
			type: 'typewriter',
			/*horizontal/horizontal/Marquee/type*/
			direction: 'right',
			/*up/down/left/right*/
			speed: 50,
			/*true/false/number*/
			/*For vertical/horizontal 600*/
			/*For marquee 0.05*/
			/*For typewriter 50*/
			controls: {
				prev: $('.acme-news-ticker-prev'),
				/*Can be used for horizontal/horizontal/typewriter*/
				/*not work for marquee*/
				toggle: $('.acme-news-ticker-pause'),
				/*Can be used for horizontal/horizontal/typewriter*/
				/*not work for marquee*/
				next: $('.acme-news-ticker-next') /*Can be used for horizontal/horizontal/marquee/typewriter*/
			}
		});
	})
</script>


<?php
	$dateString  = $campaignElection->election_date;
	$date = new DateTime($dateString);
	$formattedYear = $date->format('Y');
	$formattedMonth = $date->format('m');
	$formattedDate = $date->format('d');
?>
<script>
	(function() {
		const second = 1000,
			minute = second * 60,
			hour = minute * 60,
			day = hour * 24;

		//I'm adding this section so I don't have to keep updating this pen every year :-)
		//remove this if you don't need it
		let today = new Date(),
			dd = String(today.getDate()).padStart(2, "0"),
			mm = String(today.getMonth() + 1).padStart(2, "0"),
			// yyyy = today.getFullYear(),
			yyyy = <? echo $formattedYear;?>,
			nextYear = yyyy + 1,
			dayMonth = "<? echo $formattedMonth;?>/<? echo $formattedDate;?>/",
			birthday = dayMonth + yyyy;

		// today = mm + "/" + dd + "/" + yyyy;
		// if (today > birthday) {
		// 	birthday = dayMonth + nextYear;
		// }
		//end

		const countDown = new Date(birthday).getTime(),
			x = setInterval(function() {

				const now = new Date().getTime(),
					distance = countDown - now;

				document.getElementById("days").innerText = Math.floor(distance / (day)),
					document.getElementById("hours").innerText = Math.floor((distance % (day)) / (hour)),
					document.getElementById("minutes").innerText = Math.floor((distance % (hour)) / (minute)),
					document.getElementById("seconds").innerText = Math.floor((distance % (minute)) / second);

				//do something later when date is reached
				if (distance < 0) {
					document.getElementById("headline").innerText = "It's my birthday!";
					document.getElementById("countdown").style.display = "none";
					document.getElementById("content").style.display = "block";
					clearInterval(x);
				}
				//seconds
			}, 0)
	}());
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- <?php echo json_encode($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);?> -->

                                                                                                                   

<script>
	const ctx = document.getElementById('myChart');
	var chart = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: <?php echo json_encode($statearr); ?>,
			datasets: [{
				label: '# of Votes',
				data: <?php echo json_encode($vote_value); ?>,
				backgroundColor: [
					'rgba(255, 99, 132, 0.2)',
					'rgba(255, 159, 64, 0.2)',
					'rgba(255, 205, 86, 0.2)',
					'rgba(75, 192, 192, 0.2)',
					'rgba(54, 162, 235, 0.2)',
					'rgba(153, 102, 255, 0.2)',
					'rgba(201, 203, 207, 0.2)'
				],
				borderColor: [
					'rgb(255, 99, 132)',
					'rgb(255, 159, 64)',
					'rgb(255, 205, 86)',
					'rgb(75, 192, 192)',
					'rgb(54, 162, 235)',
					'rgb(153, 102, 255)',
					'rgb(201, 203, 207)'
				],
				borderWidth: 1
			}]
		},
		options: {
			scales: {
				y: {
					beginAtZero: true
				}
			}
		}
	});

	// $(document).ready(function() {
	// 	getYearValue(2019);
	// });

	function getYearValue(yearid) {
		$.ajax({
			type: "POST",
			url: "<?= BASEURL ?>dashboard/getpollingresultgraphuser",
			data: {
				election_year: yearid
			},
			success: function(data) {

				var vote_value = data.vote_value;
				var statearr = data.statearr;

				const ctx = document.getElementById('myChart');
				var chart = new Chart(ctx, {
					type: 'bar',
					data: {
						labels: statearr,
						datasets: [{
							label: '# of Votes',
							data: vote_value,
							backgroundColor: [
								'rgba(255, 99, 132, 0.2)',
								'rgba(255, 159, 64, 0.2)',
								'rgba(255, 205, 86, 0.2)',
								'rgba(75, 192, 192, 0.2)',
								'rgba(54, 162, 235, 0.2)',
								'rgba(153, 102, 255, 0.2)',
								'rgba(201, 203, 207, 0.2)'
							],
							borderColor: [
								'rgb(255, 99, 132)',
								'rgb(255, 159, 64)',
								'rgb(255, 205, 86)',
								'rgb(75, 192, 192)',
								'rgb(54, 162, 235)',
								'rgb(153, 102, 255)',
								'rgb(201, 203, 207)'
							],
							borderWidth: 1
						}]
					},
					options: {
						scales: {
							y: {
								beginAtZero: true
							}
						}
					}
				});
				updateConfigAsNewObject(chart)
			}
		});
	}
	function updateConfigAsNewObject(chart) {

		chart.update();
	}
</script>



<!-- new statewise graph by khushi -->

<script>
	$(document).ready(function() {
		// alert("hiiii");
		document.getElementById('hide').style.display = "none";
	var election_year = 2019;
	
		var party_name = 17;

      $.ajax({
        type: "POST",
        url: "<?= BASEURL ?>dashboard/getpollingresultgraphuser3",
        dataType: "json",
        data: {
			election_year: election_year,
				party_name: 17
        },
        success: function(response) {
          if (response) {
            var ctx = document.getElementById("myChart1");
            if (window.line != undefined) {
              window.line.destroy();
            }
            window.line = new Chart(ctx, {
              type: 'bar',
              data: {
                labels: response.state,
                datasets: [{
                  label: 'YEAR WISE ELECTION DATA',
                  data: response.votes,
				  backgroundColor: [
								'rgba(195, 172, 208,0.2)',
								'rgba(119, 67, 219, 0.2)',
								'rgba(255, 0, 99, 0.2)',
								'rgba(18, 91, 80, 0.2)',
								'rgba(250, 78, 171, 0.2)',
								'rgba(41, 44, 109, 0.2)',
								'rgba(201, 203, 207, 0.2)'
							],
							borderColor: [
								'rgb(195, 172, 208)',
								'rgb(119, 67, 219)',
								'rgb(255, 0, 99)',
								'rgb(18, 91, 80)',
								'rgb(250, 78, 171)',
								'rgb(41, 44, 109)',
								'rgb(201, 203, 207)'
							],
                  borderWidth: 1
                }]
              },
              options: {
				
                plugins: {
                  labels: {
                    render: () => {}
                  }
                },
                scales: {
                  xAxes: [{
                    gridLines: {
                      display: false
                    }
                  }],
                  yAxes: [{
                    gridLines: {
                      display: false
                    },
                    ticks: {
                      beginAtZero: true,
                    }
                  }]

                }
              },
			 
            });
          }
        }
      });
	  function updateConfigAsNewObject(ctx) {

chart.update();
}
    
});
	

</script>

<script>
	function grade_Change(yearid,party_name) {
	document.getElementById('hide').style.display = "none";
	var election_year = yearid;
	// alert(election_year);
		var party_name = 17;

      $.ajax({
        type: "POST",
        url: "<?= BASEURL ?>dashboard/getpollingresultgraphuser3",
        dataType: "json",
        data: {
			election_year: election_year,
				party_name: 17
        },
        success: function(response) {
          if (response) {
            var ctx = document.getElementById("myChart1");
            if (window.line != undefined) {
              window.line.destroy();
            }
            window.line = new Chart(ctx, {
              type: 'bar',
              data: {
                labels: response.state,
                datasets: [{
                  label: 'YEAR WISE ELECTION DATA',
                  data: response.votes,
				  backgroundColor: [
								'rgba(195, 172, 208,0.2)',
								'rgba(119, 67, 219, 0.2)',
								'rgba(255, 0, 99, 0.2)',
								'rgba(18, 91, 80, 0.2)',
								'rgba(250, 78, 171, 0.2)',
								'rgba(41, 44, 109, 0.2)',
								'rgba(201, 203, 207, 0.2)'
							],
							borderColor: [
								'rgb(195, 172, 208)',
								'rgb(119, 67, 219)',
								'rgb(255, 0, 99)',
								'rgb(18, 91, 80)',
								'rgb(250, 78, 171)',
								'rgb(41, 44, 109)',
								'rgb(201, 203, 207)'
							],
                  borderWidth: 1
                }]
              },
              options: {
				
                plugins: {
                  labels: {
                    render: () => {}
                  }
                },
                scales: {
                  xAxes: [{
                    gridLines: {
                      display: false
                    }
                  }],
                  yAxes: [{
                    gridLines: {
                      display: false
                    },
                    ticks: {
                      beginAtZero: true,
                    }
                  }]

                }
              },
			 
            });
          }
        }
      });
	  function updateConfigAsNewObject(ctx) {

chart.update();
}
    }
</script>






<!--======================Footer=======================-->