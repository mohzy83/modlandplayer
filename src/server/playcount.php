<?php
include 'config.php';

// Update Playcount
$id = $_GET["id"];
if(strlen($id)==32) {
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);

	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$stmt = $conn->prepare("UPDATE modland SET PLAYCOUNT=PLAYCOUNT+1 WHERE id=?");
	
	$stmt->bind_param("s", $id ); 
	$stmt->execute();
	$stmt->close();
	
	$conn->close();	
}

?>