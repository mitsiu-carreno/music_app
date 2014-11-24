<?php
try{
	$user="root";
	$pass="mitc5003";
	$connection = new PDO(
		'mysql:host=localhost;dbname=music_app', $user, $pass);
	
	foreach($connection ->query('select * from tracks') as $row){
		print_r($row);
	}
	$connection = null;
} catch (PDOException $e){
	print "Error!!: " . $e->getMessage() . "<br/>";
	die(); 
}
?>