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
		$this->duration = $response["duration_ms"];
		$this->popularity= $response["popularity"];
		//$this-> vecesReproducida= $response[""][""];			
	}
}

class album{
	public $idAlbum;	
	public $album;	
	public $uri;		
	public $idGenre;	
	public $idType;	
	public $image;	
	public $releaseDay;	
	public $popularity;

	function __construct($response){
	$this->idAlbum = $response[""];
	$this->album = $response[""][""];
	$this->uri = $response[""][""];
	$this->idGenre = $response[""][""];
	$this->idType = $response[""][""];
	$this->image = $response[""][""];
	$this->releaseDay = $response[""][""];
	$this->popularity = $response[""][""];
	}	
}