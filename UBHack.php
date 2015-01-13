<?php
/* BIT API PHP Library Version 0.50 (Alpha)

**  Class to integrate with BandsInTown's API
* 
*   Written by Greg Avola (http://twitter.com/gregavola)
*
*   For more documentation please refer to http://code.google.com/p/bit-php-library/
*
*   BIT.php - the class.
*
 *  
 */
class BIT {
	
	public $api_key;
	protected $apiBase = "http://api.bandsintown.com/";
	protected $userAgent = "BIT Library V-0.50 (http://code.google.com/p/bit-php-library/)";
	
	// Sets the API for the Instance of the Class
	public function setAPI($apikey)
	{
		$this->api_key = $apikey;
	}
	
	// Gets the API for the current instance of the Class. Good for debugging
	public function getAPI()
	{
		return $this->api_key;
	}
	
	// Gets Basic Information about the Artist of your Choice
	public function getArtist($artist_name)
	{
		if ($this->api_key == null || $this->api_key == "")
		{
			throw new Exception("No API Key is set.");
		}
		else
		{
			// Escape Characters
			$search = array("'", " ", "/", "?", "&");
			$replace = array("%27", "%20", "%252", "%252", "and");

			$search_artist = str_replace($search, $replace, $artist_name);

			//build the URL to pass to BandsinTown

			$url = $this->apiBase . "artists/" . $search_artist . ".xml?app_id" . $this->api_key;
      
			
			$ch = curl_init($url);

			curl_setopt($ch,CURLOPT_USERAGENT, $this->userAgent); 
		    curl_setopt($ch, CURLOPT_VERBOSE, 1);
		    curl_setopt($ch, CURLOPT_NOBODY, 0);

		    @curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$code = array();
		    $response = curl_exec($ch);

		   	$responseInfo=curl_getinfo($ch);
		    curl_close($ch);

			$code["httpCode"] = $responseInfo['http_code'];
			$code["response"] = $response;

			if ($code["httpCode"] == 200)
			{
				$entries = json_decode($code["response"]);

				return $entries;
			}
			else
			{
				throw new Exception("Request was returned with a failing httpCode: " . $code["httpCode"]);
			}
		}
	}
	
	function getEventsForSingleArtist($artist_name)
	{
		if ($this->api_key == null || $this->api_key == "")
		{
			throw new Exception("No API Key is set.");
		}
		else
		{
			// Escape Characters
			$search = array("'", " ", "/", "?", "&");
			$replace = array("%27", "%20", "%252", "%252", "and");

			$search_artist = str_replace($search, $replace, $artist_name);

			//build the URL to pass to BandsinTown

			$url = $this->apiBase . "artists/" . $search_artist . "/events.json?app_id" . $this->api_key;
			
			$ch = curl_init($url);

			curl_setopt($ch,CURLOPT_USERAGENT,$this->userAgent); 
		    curl_setopt($ch, CURLOPT_VERBOSE, 1);
		    curl_setopt($ch, CURLOPT_NOBODY, 0);

		    @curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$code = array();
		    $response = curl_exec($ch);

		   	$responseInfo=curl_getinfo($ch);
		    curl_close($ch);

			$code["httpCode"] = $responseInfo['http_code'];
			$code["response"] = $response;

			if ($code["httpCode"] == 200)
			{
				$entries = json_decode($code["response"]);

				return $entries;
			}
			else
			{
				throw new Exception($code["httpCode"]);
			}
		}
	}
	
	function eventSearch($artists = null, $location = null, $radius = 25, $date_requested = "upcoming", $page = 1, $per_page = 50)
	{
		if ($artists == null && $location == null)
		{
			throw new Exception('You need to include either an artist or a location.');
		}
		elseif ($this->api_key == null || $this->api_key == "")
		{
			throw new Exception("No API Key is set.");
		}
		elseif (!is_array($artists) && $location != null)
		{
			throw new Exception('The artists variable must be in an array format.');	
		}
		elseif (sizeof($artists) == 0 && $location == null)
		{
			throw new Exception('You must provide either a location or artists.');	
		}
		else
		{
			
			$new_artist_array = array();
			
			for ($i = 0; $i < sizeof($artists); $i++)
			{
				// Escape Characters
				$search = array("'", " ", "/", "?", "&");
				$replace = array("%27", "%20", "%252", "%252", "and");

				$search_artist = str_replace($search, $replace, $artists[$i]);
				
				$new_artists_array[$i] = $search_artist;
			}
			
			$artist_string = "";
			
			for ($i = 0; $i < sizeof($new_artists_array); $i++)
			{
				$artist_string = $artist_string . "&artists[]=" . $new_artists_array[$i];
			}
		
			//build the URL to pass to BandsinTown
			
			if ($location == null)
			{
				$url = $this->apiBase . "events/search.json?" . $artist_string . "&radius=" . $radius . "&date=" . $date_requested . "&page=" . $page . "&per_page=" . $per_page . "&app_id" . $this->api_key;
			}
			else
			{
				$url = $this->apiBase . "events/search.json?" . $artist_string . "&location=" . $location . "&radius=" . $radius . "&date=" . $date_requested . "&page=" . $page . "&per_page=" . $per_page . "&app_id" . $this->api_key;
			}
			

			$ch = curl_init($url);

			curl_setopt($ch,CURLOPT_USERAGENT,$this->userAgent); 
		    curl_setopt($ch, CURLOPT_VERBOSE, 1);
		    curl_setopt($ch, CURLOPT_NOBODY, 0);

		    @curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$code = array();
		    $response = curl_exec($ch);

		   	$responseInfo=curl_getinfo($ch);
		    curl_close($ch);

			$code["httpCode"] = $responseInfo['http_code'];
			$code["response"] = $response;

			if ($code["httpCode"] == 200)
			{
				$entries = json_decode($code["response"]);

				return $entries;
			}
			else
			{
				throw new Exception("Request was returned with a failing httpCode: " . $code["httpCode"]);
			}
		}

		
	}
	
	
	function recommendedEvents($artists = null, $location = null, $radius = 25, $date_requested = "upcoming", $page = 1, $per_page = 50)
	{
		if ($artists == null && $location == null)
		{
			throw new Exception('You need to include either an artist or a location.');
		}
		elseif ($this->api_key == null || $this->api_key == "")
		{
			throw new Exception("No API Key is set.");
		}
		elseif (!is_array($artists) && $location != null)
		{
			throw new Exception('The artists variable must be in an array format.');	
		}
		elseif (sizeof($artists) == 0 && $location == null)
		{
			throw new Exception('You must provide either a location or artists.');	
		}
		else
		{
			
			$new_artist_array = array();
			
			for ($i = 0; $i < sizeof($artists); $i++)
			{
				// Escape Characters
				$search = array("'", " ", "/", "?", "&");
				$replace = array("%27", "%20", "%252", "%252", "and");

				$search_artist = str_replace($search, $replace, $artists[$i]);
				
				$new_artists_array[$i] = $search_artist;
			}
			
			$artist_string = "";
			
			for ($i = 0; $i < sizeof($new_artists_array); $i++)
			{
				$artist_string = $artist_string . "&artists[]=" . $new_artists_array[$i];
			}
		
			//build the URL to pass to BandsinTown
			
			if ($location == null)
			{
				$url = $this->apiBase . "events/recommended.json?" . $artist_string . "&radius=" . $radius . "&date=" . $date_requested . "&page=" . $page . "&per_page=" . $per_page . "&app_id" . $this->api_key;
			}
			else
			{
				$url = $this->apiBase . "events/recommended.json?" . $artist_string . "&location=" . $location . "&radius=" . $radius . "&date=" . $date_requested . "&page=" . $page . "&per_page=" . $per_page . "&app_id" . $this->api_key;
			}
			

			$ch = curl_init($url);

			curl_setopt($ch,CURLOPT_USERAGENT,$this->userAgent); 
		    curl_setopt($ch, CURLOPT_VERBOSE, 1);
		    curl_setopt($ch, CURLOPT_NOBODY, 0);

		    @curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$code = array();
		    $response = curl_exec($ch);

		   	$responseInfo=curl_getinfo($ch);
		    curl_close($ch);

			$code["httpCode"] = $responseInfo['http_code'];
			$code["response"] = $response;

			if ($code["httpCode"] == 200)
			{
				$entries = json_decode($code["response"]);

				return $entries;
			}
			else
			{
				throw new Exception("Request was returned with a failing httpCode: " . $code["httpCode"]);
			}
		}

		
	}
}


function distance($lat1, $lon1, $lat2, $lon2, $unit) {

  $theta = $lon1 - $lon2;
  $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
  $dist = acos($dist);
  $dist = rad2deg($dist);
  $miles = $dist * 60 * 1.1515;
  $unit = strtoupper($unit);

  if ($unit == "K") {
    return ($miles * 1.609344);
  } else if ($unit == "N") {
      return ($miles * 0.8684);
    } else {
        return $miles;
      }
}

function getUserLocation() {
  
				  
				  
			//	  echo "<br>--------------------------'.'<br>";
				  
				  $ip = "128.205.61.1";
				  
				  $url = "http://freegeoip.net/xml/".$ip;
				  $xml_feed_url = $url;
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $xml_feed_url);
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$xml_s = curl_exec($ch);
				curl_close($ch);
				  
				  $string_data = $url;
				$xml = simplexml_load_string($xml_s);


return $xml;

}



function getalldetails($artistArray,$GeoLocation){

include 'getMBID.php';

$myBIT = new BIT();
$myBIT->setAPI("APIKEYHERE");
$myLat = 42.9523960;
$userLoc = $GeoLocation;
$mbid = musicbrain($artistArray);
$artistArray = $mbid;
$gigArray = Array();


foreach($artistArray as $artist)
{
try
{
   // set the Artist that you want to search. Make sure you leave it plain text - do not do any URL encoding. The class file will take care of that for you.
  
			
	// perform the get Artist function
	$results = $myBIT->getEventsForSingleArtist($artist);
			
	// loop through the response
	foreach($results as $events)
	{
	   $dateofevent = date("m/d/y H:i", strtotime($events->datetime));
	   $venue_city = $events->venue->city;
	   $venue_region = $events->venue->region;
	   $venue_name = $events->venue->name;
       $venue_latitude = $events->venue->latitude;
       $venue_longitute = $events->venue->longitude;
       $tickets =$events->ticket_url;
       $dis = distance($userLoc[0],$userLoc[1],$venue_latitude,$venue_longitute,'M');
       $dis = round($dis,2);
	
	
		$gig = array();
		$gig['ArtistName'] = $events->artists[0]->name; //$artist;
		$gig['datetime'] = $dateofevent;
		$gig['vanue']['location'] = $events->venue->name;
		$gig['vanue']['city'] = $events->venue->city;
		$gig['vanue']['region'] = $events->venue->region;
		$gig['vanue']['country'] = $events->venue->country;
		$gig['vanue']['latitude'] = $events->venue->latitude;
		$gig['vanue']['longitude'] = $events->venue->longitude;
		$gig['TicketUrl'] = $tickets; //$artist;

		$gigArray[$dis] = $gig;

	}
}
catch(Exception $e)
{
	// If you have an error from the request - it will be displayed here.
	"window.location.replace('http://localhost/home.php')";
	//sleep(2);
}

}

ksort($gigArray);

return $gigArray;
}
?>