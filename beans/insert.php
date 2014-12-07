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

	//var_dump($_POST["album"]);

	//TEsting

	<?php
class Contact
{
    private $UploadedFiles = '';
    private $DbHost = DB_HOST;
    private $DbName = DB_NAME;
    private $DbUser = DB_USER;
    private $DbPass = DB_PASS;
    private $table;

    function __construct()
    {
        $this->table = strtolower(get_class());
    }

    public function insert($values = array())
    {

        foreach ($values as $field => $v)
            $ins[] = ':' . $field;

        $ins = implode(',', $ins);
        $fields = implode(',', array_keys($values));
        $sql = "INSERT INTO $this->table ($fields) VALUES ($ins)";

        $sth = $dbh->prepare($sql);
        foreach ($values as $f => $v)
        {
            $sth->bindValue(':' . $f, $v);
        }
        $sth->execute();
        //return $this->lastId = $dbh->lastInsertId();
    }