<?php

function openDb($dbHost = 'mysql02', $dbUsername = 'skm_dev', $dbPassword = 'irg3nd', $dbName = 'skm_dev')
{	
	//@TODO use db credentials as parameter and remove global
	// global $dbHost,$dbUsername,$dbPassword,$dbName;

	//@TODO check if db is already open and return that resource
	//@TODO use global db connection

	// Create connection
	$con=mysqli_connect($dbHost,$dbUsername,$dbPassword,$dbName);

	// Check connection
	if (mysqli_connect_errno($con))
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }

	return $con;
}

// query DB
function queryDb($con, $query)
{
	return mysqli_query($con,$query);
}

// Close DB
function closeDb($con)
{
	mysqli_close($con);

	//TODO We could do the following if we where paranoid, but then we would also have to check in wich more variables these contents are saved
	// unset($user, $pass, $server);  // Flush from memory.
}



?>
