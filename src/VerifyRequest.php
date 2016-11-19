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
	$sql = "SELECT * FROM Form WHERE Email = '$email'";
	$result = $conn->query($sql);
	if($row = $result->fetch_assoc()){
		$name = $row["name"];
		$faculty = $row["faculty"];
		$department = $row["department"];
		$class = $row["class"];
		$registration = $row["registration"];
		$roll = $row["roll"];
		$email = $row["email"];
		$session = $row["session"];
		$password = $row["password"];
		$sql = "INSERT INTO Student (name, faculty, department, class, registration, roll, email, session, password)
		VALUES ('$name', '$faculty', '$department', '$class', '$registration', '$roll', '$email', '$session', '$password')";
		if($conn->query($sql) === TRUE) {
			$sql = "DELETE FROM Form WHERE Email = '$email'";
		    $conn->query($sql);
		    header("refresh:0; url=Requests.php");
		}
		else{
		    echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
	$conn->close();
?>
</body>
</html>