<?php

	$con=new mysqli("localhost","root","","mydb");

	if($con->connect_error){
		die("Connection failed: " . $con->connect_error);
	}
?>
