<?php
  function musicbrain($artistArray) {
  $artist_name = $artistArray;
	  
	  $search = array("'", " ", "/", "?", "&");
	$replace = array("%27", "%20", "%252", "%252", "and");

	$artist = str_replace($search, $replace, $artist_name);
	for ($k = 0; $k < sizeof($artist); $k++){
	$url = "http://musicbrainz.org/ws/2/artist/?query=artist:".$artist[$k];
	$xml_feed_url = $url;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $xml_feed_url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$xml_s = curl_exec($ch);
	curl_close($ch);

	$string_data = $url;
	$xml = simplexml_load_string($xml_s);
  $mbID = (String)$xml->{'artist-list'}->artist['id'];
  $mb[$k] = 'mbid_'.$mbID;}
  return $mb;
  
  }
?>