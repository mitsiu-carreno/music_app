<?php 
	foreach($db ->query('select * from tracks') as $row){
		print_r($row);
	}
?>