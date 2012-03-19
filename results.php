<?php
//sandbox change
require 'php-simplegeo/SimpleGeo.php';

$client = new SimpleGeo('xxx', 'xxx');

function mysql_prep($value) {
	$magic_quotes_active = get_magic_quotes_gpc();
	$new_enough_php = function_exists("mysql_real_escape_string"); //i.e. PHP >= v 4.3.0
	if($new_enough_php) {
		//undo any magic quote effects so mysql_real_escape_string can do the work
		if($magic_quotes_active) {
			$value = stripslashes($value);
		}
		$value = mysql_real_escape_string($value);
	} else { //before PHP v 4.3.0
		//if magic quotes aren't alredy on then add slashes manually
		if(!$magic_quotes_active) {
			$value = addslashes($value); 
		}//if magic quotes are active then the slashes already exist
		
		}
	return $value; 
}
function redirect_to($page) {
	header("Location:{$page}");
	exit;
}
/* Using a lat/lon coordinate */
// $lat = 52.6844417;
// $lon = 6.539572899999939;
// $result = $client->ContextCoord($lat, $lon);
if(isset($_POST['location'])) {
	$address = $_POST['location']; 
	function startsWithNumber($address) {
	    return strlen($address) > 0 && ctype_digit(substr($address, 0, 1));
	}
	$is_address = startsWithNumber($address);
	if($is_address != 1) {
		redirect_to("index.php?address=1");
	}
/* Using a street address */
$full_address = $address; //'41 Decatur St., San Francisco, CA 94109';
$address_result = $client->ContextAddress($full_address);
//print_r($address_result);
$lat =  $address_result['query']['latitude'];
$long =  $address_result['query']['longitude'];
//echo $lat;
$table = "B01001"; //population of male sex by age table. 
$result = $client->ContextDemographics($lat, $long, $table);
//print_r($result['demographics']);
// /* Using an IP address */
// $ip = '8.8.8.8';
// $result = $client->ContextIP($ip);
//print_r($result['demographics']['estimate']);
$population_density = $result['demographics']['population_density'];
$population_estimate = $result['demographics']['acs']['B01001']['records'][0]['estimate'];

$total_male = $result['demographics']['acs']['B01001']['records'][1]['estimate'];

$male_18_19 = $result['demographics']['acs']['B01001']['records'][6]['estimate'];
$male_20 = $result['demographics']['acs']['B01001']['records'][7]['estimate'];
$male_21 = $result['demographics']['acs']['B01001']['records'][8]['estimate'];
$male_22_24 = $result['demographics']['acs']['B01001']['records'][9]['estimate'];
$male_25_29 = $result['demographics']['acs']['B01001']['records'][10]['estimate'];
$male_25_29 = $result['demographics']['acs']['B01001']['records'][10]['estimate'];
$male_30_34 = $result['demographics']['acs']['B01001']['records'][11]['estimate'];
$male_35_39 = $result['demographics']['acs']['B01001']['records'][12]['estimate'];
$male_40_44 = $result['demographics']['acs']['B01001']['records'][13]['estimate'];
$male_45_49 = $result['demographics']['acs']['B01001']['records'][14]['estimate'];
$male_50_54 = $result['demographics']['acs']['B01001']['records'][15]['estimate'];
$male_55_59 = $result['demographics']['acs']['B01001']['records'][16]['estimate'];
$male_60_61 = $result['demographics']['acs']['B01001']['records'][17]['estimate'];
$male_62_64 = $result['demographics']['acs']['B01001']['records'][18]['estimate'];

$total_female = $result['demographics']['acs']['B01001']['records'][25]['estimate'];

$female_18_19 = $result['demographics']['acs']['B01001']['records'][30]['estimate'];
$female_20 = $result['demographics']['acs']['B01001']['records'][31]['estimate'];
$female_21 = $result['demographics']['acs']['B01001']['records'][32]['estimate'];
$female_22_24 = $result['demographics']['acs']['B01001']['records'][33]['estimate'];
$female_25_29 = $result['demographics']['acs']['B01001']['records'][34]['estimate'];
$female_25_29 = $result['demographics']['acs']['B01001']['records'][35]['estimate'];
$female_30_34 = $result['demographics']['acs']['B01001']['records'][36]['estimate'];
$female_35_39 = $result['demographics']['acs']['B01001']['records'][37]['estimate'];
$female_40_44 = $result['demographics']['acs']['B01001']['records'][38]['estimate'];
$female_45_49 = $result['demographics']['acs']['B01001']['records'][39]['estimate'];
$female_50_54 = $result['demographics']['acs']['B01001']['records'][40]['estimate'];
$female_55_59 = $result['demographics']['acs']['B01001']['records'][41]['estimate'];
$female_60_61 = $result['demographics']['acs']['B01001']['records'][42]['estimate'];
$female_62_64 = $result['demographics']['acs']['B01001']['records'][43]['estimate'];


//$total_population = ($result['demographics']['acs']['logrecno'])*6 . " ($city, $state area. <i>Some sample areas are smaller than others. Population density is more accurate comparison between dealers.</i>)";

$table2 = "B19001"; 
$income = $client->ContextDemographics($lat, $long, $table2);
$income_10_14 = $income['demographics']['acs']['B19001']['records'][2]['estimate'];
$income_15_19 = $income['demographics']['acs']['B19001']['records'][3]['estimate'];
$income_20_24 = $income['demographics']['acs']['B19001']['records'][4]['estimate'];
$income_25_29 = $income['demographics']['acs']['B19001']['records'][5]['estimate'];
$income_30_34 = $income['demographics']['acs']['B19001']['records'][6]['estimate'];
$income_35_39 = $income['demographics']['acs']['B19001']['records'][7]['estimate'];
$income_40_44 = $income['demographics']['acs']['B19001']['records'][8]['estimate'];
$income_45_49 = $income['demographics']['acs']['B19001']['records'][9]['estimate'];
$income_50_59 = $income['demographics']['acs']['B19001']['records'][10]['estimate'];
$income_60_74 = $income['demographics']['acs']['B19001']['records'][11]['estimate'];
$income_75_99 = $income['demographics']['acs']['B19001']['records'][12]['estimate'];
$income_100_124 = $income['demographics']['acs']['B19001']['records'][13]['estimate'];
$income_125_149 = $income['demographics']['acs']['B19001']['records'][14]['estimate'];
$income_150_199 = $income['demographics']['acs']['B19001']['records'][15]['estimate'];

$table3 = "B01002"; //median age by sex
$median_age = $client->ContextDemographics($lat, $long, $table3);
//print_r($median_age);
$median_age_print = $median_age['demographics']['acs']['B01002']['records'][0]['estimate'];

$table4 = "B02001"; //race
$race = $client->ContextDemographics($lat, $long, $table4);
$white = $race['demographics']['acs']['B02001']['records'][1]['estimate'];
$black = $race['demographics']['acs']['B02001']['records'][2]['estimate'];
$american_indian = $race['demographics']['acs']['B02001']['records'][3]['estimate'];
$asian = $race['demographics']['acs']['B02001']['records'][4]['estimate'];
$pacific_islander = $race['demographics']['acs']['B02001']['records'][5]['estimate'];
$other_race = $race['demographics']['acs']['B02001']['records'][6]['estimate'];
//print_r($race['demographics']);

$table5 = "B08103"; //aggregate travel time to work
$transportation = $client->ContextDemographics($lat, $long, $table5);
$auto_drove_alone = $transportation['demographics']['acs']['B08103']['records'][1]['estimate'];
$auto_carpooled = $transportation['demographics']['acs']['B08103']['records'][2]['estimate'];
$public_transportation = $transportation['demographics']['acs']['B08103']['records'][3]['estimate'];
$walked = $transportation['demographics']['acs']['B08103']['records'][4]['estimate'];
$taxi_moto_bike_other = $transportation['demographics']['acs']['B08103']['records'][5]['estimate'];
$work_at_home = $transportation['demographics']['acs']['B08103']['records'][6]['estimate'];

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>QMR - Quick Market Research</title>
    
    <!-- Include CSS -->
    <link href="css/reset.css" rel="stylesheet" type="text/css" />
    <link href="css/style2.css" rel="stylesheet" type="text/css" />
	<link href="css/demo.css" rel="stylesheet" type="text/css" />
    <link href="css/slimbox2.css" rel="stylesheet" type="text/css" />
    <link href='http://fonts.googleapis.com/css?family=Oswald|Droid+Sans:400,700' rel='stylesheet' type='text/css' />

    <!-- Include Scripts -->	
    <script type="text/javascript" src="js/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="js/jquery.cycle.lite.min.js"></script>
    <script type="text/javascript" src="js/jquery.pngFix.pack.js"></script>
    <script type="text/javascript" src="js/slimbox2.js"></script>
    <script type="text/javascript" src="js/custom.js"></script>	

</head>

<body>

<?php include("header.php"); ?>


<!-- HEADER DIVIDER -->
<div id="head-break">

	<p>Stats + Figures</p>
	<h1><a href="#" style="color:#ffffff;">Your Results For <?php if($address) echo $address;?></a></h1>
    
</div><!-- END HEADER DIVIDER -->


<!-- START MAIN CONTAINER -->
<div class="container">
	<h3 style="color:#ffffff;">Population Density: <?php if($population_density) echo $population_density; ?> (km2)</h3>
	<h3 style="color:#ffffff;">Population Estimate for the Neighborhood Surrounding this Address: <?php if($population_estimate) echo $population_estimate; ?></h3>
	<p style="color:#ffffff;">This neighborhood population is calculated from the census track your address belongs to. To see the geographical size of the census track for your address, go here: <a href="http://projects.nytimes.com/census/2010/explorer" target="_blank">Census Track Size.</a> &nbsp;Moreover, all values below are also calculated for the census track region your address belongs to (a good metric for assessing the immediate business value of an address, because the census tract region generally corresponds to size of a neighborhood).</p><br/>
	
	<h3 style="color:#ffffff;">Income Distribution</h3>
	<table id="example3">
		<caption></caption>
		<thead>
			<tr>
				<th>Item</th>
				<th>Quantity</th>
			</tr>
		</thead>
		<tbody>
				<tr>
					<td>$10,000 to $14,999</td>
					<td><?php echo $income_10_14; ?></td>
				</tr>
				<tr>
					<td>$15,000 to $19,999</td>
					<td><?php echo $income_15_19; ?></td>
				</tr>
				<tr>
					<td>$20,000 to $24,999</td>
					<td><?php echo $income_20_24; ?></td>
				</tr>
				<tr>
					<td>$25,000 to $29,999</td>
					<td><?php echo $income_25_29; ?></td>
				</tr>
				<tr>
					<td>$30,000 to $34,999</td>
					<td><?php echo $income_30_34; ?></td>
				</tr>
				<tr>
					<td>$35,000 to $39,999</td>
					<td><?php echo $income_35_39; ?></td>
				</tr>
				<tr>
					<td>$40,000 to $44,999</td>
					<td><?php echo $income_40_44; ?></td>
				</tr>
				<tr>
					<td>$45,000 to $49,999</td>
					<td><?php echo $income_45_49; ?></td>
				</tr>
				<tr>
					<td>$50,000 to $59,999</td>
					<td><?php echo $income_50_59; ?></td>
				</tr>
				<tr>
					<td>$60,000 to $74,999</td>
					<td><?php echo $income_60_74; ?></td>
				</tr>
				<tr>
					<td>$75,000 to $79,999</td>
					<td><?php echo $income_75_79; ?></td>
				</tr>
				<tr>
					<td>$100,000 to $124,999</td>
					<td><?php echo $income_100_124; ?></td>
				</tr>
				<tr>
					<td>$125,000 to $149,999</td>
					<td><?php echo $income_125_149; ?></td>
				</tr>
				<tr>
					<td>$150,000 to $199,999</td>
					<td><?php echo $income_150_199; ?></td>
				</tr>
		</tbody>
	</table>
	<br/><br/>
	<h3 style="color:#ffffff;">Gender and Age Distribution</h3>
	<table id="example2">
		<caption></caption>
		<thead>
			<tr>
				<th>Gender</th>
				<th>Item</th>
				<th>Quantity</th>
			</tr>
		</thead>
		<tbody>
				<tr>
					<td>Male</td>
					<td>18 to 19 years</td>
					<td><?php echo $male_18_19; ?></td>
				</tr>
				<tr>
					<td>Male</td>
					<td>20 years</td>
					<td><?php echo $male_20; ?></td>
				</tr>
				<tr>
					<td>Male</td>
					<td>21 years</td>
					<td><?php echo $male_21; ?></td>
				</tr>
				<tr>
					<td>Male</td>
					<td>22 to 24 years</td>
					<td><?php echo $male_22_24; ?></td>
				</tr>
				<tr>
					<td>Male</td>
					<td>25 to 29 years</td>
					<td><?php echo $male_25_29; ?></td>
				</tr>
				<tr>
					<td>Male</td>
					<td>30 to 34 years</td>
					<td><?php echo $male_30_34; ?></td>
				</tr>
				<tr>
					<td>Male</td>
					<td>35 to 39 years</td>
					<td><?php echo $male_35_39; ?></td>
				</tr>
				<tr>
					<td>Male</td>
					<td>40 to 44 years</td>
					<td><?php echo $male_40_44; ?></td>
				</tr>
				<tr>
					<td>Male</td>
					<td>45 to 49 years</td>
					<td><?php echo $male_45_49; ?></td>
				</tr>
				<tr>
					<td>Male</td>
					<td>50 to 54 years</td>
					<td><?php echo $male_50_54; ?></td>
				</tr>
				<tr>
					<td>Male</td>
					<td>60 to 61 years</td>
					<td><?php echo $male_60_61; ?></td>
				</tr>
				<tr>
					<td>Male</td>
					<td>62 to 64 years</td>
					<td><?php echo $male_62_64; ?></td>
				</tr>
				
				<tr>
					<td>Female</td>
					<td>18 to 19 years</td>
					<td><?php echo $female_18_19; ?></td>
				</tr>
				<tr>
					<td>Female</td>
					<td>20 years</td>
					<td><?php echo $female_20; ?></td>
				</tr>
				<tr>
					<td>Female</td>
					<td>21 years</td>
					<td><?php echo $female_21; ?></td>
				</tr>
				<tr>
					<td>Female</td>
					<td>22 to 24 years</td>
					<td><?php echo $female_22_24; ?></td>
				</tr>
				<tr>
					<td>Female</td>
					<td>25 to 29 years</td>
					<td><?php echo $female_25_29; ?></td>
				</tr>
				<tr>
					<td>Female</td>
					<td>30 to 34 years</td>
					<td><?php echo $female_30_34; ?></td>
				</tr>
				<tr>
					<td>Female</td>
					<td>35 to 39 years</td>
					<td><?php echo $female_35_39; ?></td>
				</tr>
				<tr>
					<td>Female</td>
					<td>40 to 44 years</td>
					<td><?php echo $female_40_44; ?></td>
				</tr>
				<tr>
					<td>Female</td>
					<td>45 to 49 years</td>
					<td><?php echo $female_45_49; ?></td>
				</tr>
				<tr>
					<td>Female</td>
					<td>50 to 54 years</td>
					<td><?php echo $female_50_54; ?></td>
				</tr>
				<tr>
					<td>Female</td>
					<td>60 to 61 years</td>
					<td><?php echo $female_60_61; ?></td>
				</tr>
				<tr>
					<td>Female</td>
					<td>62 to 64 years</td>
					<td><?php echo $female_62_64; ?></td>
				</tr>
		</tbody>
	</table>
	<br/>
 		<?php
			if($median_age_print) { echo "<br/><br/><p style='color:#ffffff; font-size:16px; padding-top:15px;'>Median Age: $median_age_print</p>"; }
			
		?> 
				<br/><br/>
				<h3 style="color:#ffffff;">Ethnicity Distribution</h3>
				<table id="example4">
					<caption></caption>
					<thead>
						<tr>
							<th>Item</th>
							<th>Quantity</th>
						</tr>
					</thead>
					<tbody>
							<tr>
								<td><span style="color:#ffffff;">White</span></td>
								<td><?php echo $white; ?></td>
							</tr>
							<tr>
								<td><span style="color:#ffffff;">Black</span></td>
								<td><?php echo $black; ?></td>
							</tr>
							<tr>
								<td><span style="color:#ffffff;">American Indian</span></td>
								<td><?php echo $american_indian; ?></td>
							</tr>
							<tr>
								<td><span style="color:#ffffff;">Asian</span></td>
								<td><?php echo $asian; ?></td>
							</tr>
							<tr>
								<td><span style="color:#ffffff;">Pacific Islander</span></td>
								<td><?php echo $pacific_islander; ?></td>
							</tr>
							<tr>
								<td><span style="color:#ffffff;">Other</span></td>
								<td><?php echo $other_race; ?></td>
							</tr>
					</tbody>
				</table>
								<br/><br/>
				<h3 style="color:#ffffff;">Transportation to Work</h3>
				<table id="example5">
					<caption></caption>
					<thead>
						<tr>
							<th>Item</th>
							<th>Quantity</th>
						</tr>
					</thead>
					<tbody>
							<tr>
								<td><span style="color:#ffffff;">Automobile - Drove Alone</span></td>
								<td><?php echo $auto_drove_alone; ?></td>
							</tr>
							<tr>
								<td><span style="color:#ffffff;">Automobile - Carpooled</span></td>
								<td><?php echo $auto_carpooled; ?></td>
							</tr>
							<tr>
								<td><span style="color:#ffffff;">Public Transportation</span></td>
								<td><?php echo $public_transportation; ?></td>
							</tr>
							<tr>
								<td><span style="color:#ffffff;">Walked</span></td>
								<td><?php echo $walked; ?></td>
							</tr>
							<tr>
								<td><span style="color:#ffffff;">Taxi, motorcycle, bike, other</span></td>
								<td><?php echo $taxi_moto_bike_other; ?></td>
							</tr>
							<tr>
								<td><span style="color:#ffffff;">Work at Home</span></td>
								<td><?php echo $work_at_home; ?></td>
							</tr>
					</tbody>
				</table>
</div><!-- END MAIN CONTAINER -->
<br/>
<br/>


<!-- START SUB TILES ONE -->
<div id="sub-tiles" >
<!-- START FEATURE TILES -->
<div id="feature-tiles" style="margin:0;">

	<div class="container">
    
    	<?php include("footer-features.php"); ?>
        
        <br class="clear" />
    
    </div>

</div><!-- END FEATURE TILES -->


<?php include("footer.php"); ?>
<script type="text/javascript" src="js/jquery.charts.js"></script>

	<script type="text/javascript">
	$("#example4").charts({ direction: "horizontal", showgrid: false, chartbgcolours: ["#c94c00", "#c92b00", "#741b03"], chartfgcolours: ["#ffffff", "#ffffff", "#ffffff"] });
	$("#example5").charts({ direction: "horizontal", showgrid: false, chartbgcolours: ["#c94c00", "#c92b00", "#741b03"], chartfgcolours: ["#ffffff", "#ffffff", "#ffffff"] });
		$("#example2").charts({ direction: "vertical", showgrid: false, labelcolumn: 1, valuecolumn: 2, groupcolumn: 0 });
		$("#example3").charts({ direction: "vertical", showgrid: false });
		
	</script>
</body>
</html>