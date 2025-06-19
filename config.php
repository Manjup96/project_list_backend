<?php

		
$host="localhost:3307";
$username="root";
$pass="";

$db="project_list";

// $servername = "127.0.0.1";
// $username = "root";
// $password = "Root@1234";
// $database = "project_list";
// $port = 3306;


		$conn=new mysqli($host,$username,$pass,$db);
		
		if($conn->connect_error)
		{
			die("connection failed:" . $conn->connect_error);
		}


		?>

