<?php
try{
	$user="root";
	$pass="mitc5003";
	$db = new PDO(
		'mysql:host=localhost;dbname=music_app', $user, $pass);
	
	
	foreach($db ->query('select * from albumTypes') as $row){
		print_r($row);
	}
	echo 'test';
	//$db = null;
} catch (PDOException $e){
	print "Error!!: " . $e->getMessage() . "<br/>";
	die(); 
}

/* FUNCTIONAL EXAMPLE

class person {
    public $name;
    public $addr;
    public $city;
 
    function __construct($n,$a,$c) {
        $this->name = $n;
        $this->addr = $a;
        $this->city = $c;
    }
    # etc ...
}
 
$cathy = new person('Cathy','9 Dark and Twisty','Cardiff');
 
$STH = $db->prepare("INSERT INTO folks (name, addr, city) value (:name, :addr, :city)");
$STH->execute((array)$cathy);

*/