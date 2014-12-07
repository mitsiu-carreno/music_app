<?php
/*
class person {
    public $name;
    public $addr;
    public $city;
 
    function __construct($n,$a,$c) {
        $this->name = $n;
        $this->addr = $a;
        $this->city = $c;
    }
}
*/

class track{

	public $idTrack;
	public $track; 
	public $uri; 
	public $idAlbum; 
	public $duration; 
	public $popularity; 
	public $vecesReproducida;
		
	function __construct($response){
			$this->track = $response["id"];
		}
	}

	