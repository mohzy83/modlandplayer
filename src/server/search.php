<?php

include 'config.php';

header("Content-Type: application/json; charset=UTF-8");

$searchString = htmlspecialchars_decode($_GET["s"]);
if(strlen($searchString)>2) {
	// connect to the mysql database

	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$stmt = $conn->prepare("select * from modland where upper(artist) like ? or songname like ? order by artist, songname");
	$name = "%".strtoupper($searchString)."%";
	$stmt->bind_param("ss", $name, $name ); 
	$stmt->execute();
	$result = $stmt->get_result();

	echo '[';
	if($result->num_rows>0) {
		$i=0;
		while($row = $result->fetch_object()) {
			echo ($i>0?',':'').json_encode($row);
			$i++;
		}
	}
	echo ']';

	// close mysql connection
	$stmt->close();
	$conn->close();
}
?>