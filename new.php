<?php

	if(isset($_POST['artistAr']) && isset($_POST['location'])){ 
		//header('Content-Type: application/json');
		$artistArray = json_decode($_POST['artistAr']); 
		$GeoLocation = json_decode($_POST['location']); 
		include'UBHack.php' ;
		$gigs = getalldetails($artistArray,$GeoLocation);
		ksort($gigs); 
		
		
		echo json_encode($gigs);
	}
	else
		echo "false";
?>