<?php
// connect and login to FTP server
$ftp_server = "ftp.modland.com";
$ftp_username = "anonymous";
$ftp_conn = ftp_connect($ftp_server) or die("Could not connect to $ftp_server");
$login = ftp_login($ftp_conn, $ftp_username, '');

$local_file = tmpfile(); //getenv("DOCUMENT_ROOT") . "/shorty.xm";
$path = stream_get_meta_data($local_file)['uri']; // eg: /tmp/phpFx0513a
$basePath = htmlspecialchars_decode($_GET["module"]);
$server_file = "/pub/modules/" . $basePath ; //  Fasttracker 2/2Pac/shorty.xm";

// download server file
if (ftp_fget($ftp_conn, $local_file, $server_file, FTP_BINARY, 0)) {
    //echo "Successfully written to $local_file.\n";
  
	$file = $path;

	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="' . basename($basePath) .'"');
	//header('Expires: 0');
	//header('Cache-Control: must-revalidate');
	header('Cache-Control: max-age=3600');
	header('Pragma: public');
	header('Content-Length: ' . filesize($file));
	//ob_start("ob_gzhandler");
	readfile($file); 
	//ob_end_flush();	
  
} else { 
	echo "Error downloading $server_file.\n";
}

// close connection
ftp_close($ftp_conn);
fclose($local_file);
?>