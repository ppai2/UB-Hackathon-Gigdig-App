<?php
//function  findLocationXml($gigArray){
	if(isset($_POST['gigs'])){ 
			//Header('Content-type: text/xml');	
			
			$gigArray = json_decode($_POST['gigs'],true); 
			$locationXml = new SimpleXMLElement('<markers/>');
			
			foreach ($gigArray as $key => $val) {
				$newnode = $locationXml->addChild('marker');
				$newnode->addAttribute("name",$val['ArtistName']);
				$address = $val['vanue']['location'].', '.$val['vanue']['city'].', '.$val['vanue']['region'];
				$newnode->addAttribute("address", $address);
				$newnode->addAttribute("datetime", $val['datetime']);
				$newnode->addAttribute("lat", $val['vanue']['latitude']);
				$newnode->addAttribute("lng", $val['vanue']['longitude']);
				$newnode->addAttribute("type", 'gig');
			}


			//print_r($locationXml);
			//print($locationXml->asXML());
			$locationXml->asXML('map.xml');
			$json = json_encode($locationXml);
			
			echo $json;
	
	}
	
	else
		echo "false";
?>