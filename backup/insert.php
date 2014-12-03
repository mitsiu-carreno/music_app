<?php

	/*$sql = $db->prepare("insert into tracks
		(id_track, track_name, id_artist, id_album, popularity, uri) 
		values (:id_track, :track_name, :id_artist, :id_album, :popularity, :uri)");
$sql->execute(array(':id_track'=>$, ':track_name'=>$, ':id_artist'=>$, ':id_album'=>$, ':popularity'=>$, ':uri'=>$))*/


	$track = null;
	/*
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
*/

	var_dump($_POST["album"]);