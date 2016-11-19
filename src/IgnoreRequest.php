<!DOCTYPE html>
<html>
<body>
<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dbname = "PDS";
	$conn = new mysqli($servername, $username, $password, $dbname);
	if($conn->connect_error) {
		echo "Connection Failed ";
	    die("Connection failed: " . $conn->connect_error);
	}
	$email = $_GET["email"];
	$sql = "DELETE FROM Form WHERE Email = '$email'";
	$conn->query($sql);
	header("refresh:0; url=Requests.php");
	$conn->close();
?>
</body>
</html>