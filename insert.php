<?php

	$track = null;
	
	try {
 	 	$response = $_POST['response'];
  		$track = new Track($response);
	} catch(Exception $ex){
  		header("HTTP/1.0 502 Data cannot be retreive");
	}

	try{
		$model = new TrackModel($track);
		$mode->insert();
	} catch(Exception $ex){
  		header("HTTP/1.0 502 Data cannot be saved");
	}

	var_dump($_POST);