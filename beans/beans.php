<?php

class track{

	public $idTrack;
	public $track; 
	public $uri; 
	public $idAlbum; 
	public $duration; 
	public $popularity; 
	public $vecesReproducida;
		
	function __construct($response){
		$this->idTrack = $response["id"];
		$this->track = $response["name"];
		$this->uri = $response["uri"];
		$this->idAlbum = $response["album"]["id"];
		$this-> duration = $response["duration_ms"];
		$this-> popularity= $response["popularity"];
		//$this-> vecesReproducida= $response[""][""];			
	}
}

	