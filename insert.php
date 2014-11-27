<?php

$sql = $db->prepare("insert into tracks
		(id_track, track_name, id_artist, id_album, popularity, uri) 
		values (:id_track, :track_name, :id_artist, :id_album, :popularity, :uri)");
$sql->execute(array(':id_track'=>$, ':track_name'=>$, ':id_artist'=>$, ':id_album'=>$, ':popularity'=>$, ':uri'=>$));