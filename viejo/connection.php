<?php
try{
	$user="root";
	$pass="mitc5003";
	$db = new PDO(
		'mysql:host=localhost;dbname=music_app', $user, $pass);
	
	foreach($db ->query('select * from tracks') as $row){
		print_r($row);
	}
	//$db = null;
} catch (PDOException $e){
	print "Error!!: " . $e->getMessage() . "<br/>";
	die(); 
}
