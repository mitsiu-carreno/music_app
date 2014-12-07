<?php

	class Tracks{

		private $idTrack, $track, $uri, $idAlbum, $duration, $popularity, $vecesReproducida;

		function __construct(response){
			$this->idTrack = response.name;
		}
	}

	