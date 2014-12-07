<?php
include 'Track.php';

//$cathy = new person('Cathy','9 Dark and Twisty','Cardiff');
//var_dump($cathy->name);
	$track = null;

	
	try {
 	 	$response = $_POST['name'];
  		$track = new track($response);
        var_dump($response);
	} catch(Exception $ex){
  		header("HTTP/1.0 502 Data cannot be retreive");
	}
    /*
	try{
		$model = new TrackModel($track);
		$mode->insert();
	} catch(Exception $ex){
  		header("HTTP/1.0 502 Data cannot be saved");
	}
*/
