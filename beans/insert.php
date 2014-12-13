<?php
require_once 'beans.php';
//require_once 'beans2.php';

	$track = null;
	try {
 	 	$track_json = $_POST["track"];
  		//$track = new track($track_json);
        //var_dump($track->idTrack);
		//var_dump($track->track);
		//var_dump($track->uri);
		//var_dump($track->idAlbum);
		//var_dump($track->duration);

 	 	$album_json = $_POST["album"];
		$album = new album($album_json);
	} catch(Exception $ex){
  		header("HTTP/1.0 502 Data cannot be retreive");
	}
    /*
	try{
		$model = new TrackModel($track);
		$model->insert();
	} catch(Exception $ex){
  		header("HTTP/1.0 502 Data cannot be saved");
	}
*/
