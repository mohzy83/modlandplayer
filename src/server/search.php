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




	
	$wherec = " and (upper(artist) like ? or songname like ?) ";
	$finalwhere = "";	
	$stypes = "";
	$searchWords = explode(" ", $searchString);
	$array = array();
	
		
	
	foreach ($searchWords as &$value) {
		$trimmedWord = trim($value);
		if (strlen($trimmedWord) > 0) {
		//	echo $trimmedWord;
			 $finalwhere = $finalwhere . $wherec;
		//	 echo $finalwhere;
			 $stypes = $stypes . "ss";
			 $name = "%".strtoupper($trimmedWord)."%";
		//	 echo $name;
			 array_push($array, $name, $name);
		}
	}
	
	
	if(strlen($finalwhere) > 0) {	
	
		$stmt = $conn->prepare("select * from modland where 1=1 " . $finalwhere .  " order by artist, songname");
		//$name = "%".strtoupper($searchString)."%";
		$stmt->bind_param($stypes, ...$array ); 
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
	} else {
		echo '[]';
	}

	// close mysql connection
	$stmt->close();
	$conn->close();
} else {
	echo '[]';
}
?>