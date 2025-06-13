<?php

		
$host="localhost:3306";
$username="root";
$pass="";

$db="project_list";


		
		$conn=new mysqli($host,$username,$pass,$db);
		
		if($conn->connect_error)
		{
			die("connection failed:" . $conn->connect_error);
		}


		?>

