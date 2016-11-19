<html>
<body>

<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "PDS";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if($conn->connect_error) {
	echo "Connection Failed ";
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_SESSION["email"]) == FALSE || isset($_SESSION["password"]) == FALSE){
	if(isset($_POST["email"]) == FALSE || isset($_POST["password"]) == FALSE){
		header("refresh:0; url=SignInForm.php");
		die();
	}
	else{
		$email = mysqli_real_escape_string($conn, $_POST['email']);
		$password = mysqli_real_escape_string($conn, $_POST['password']);
	}
}
else{
	$email = $_SESSION["email"];
	$password = $_SESSION["password"];
}

$admins = "SELECT * FROM Admin WHERE Email = '$email' AND Password = '$password'";
$adminResult = $conn->query($admins);

$teachers = "SELECT * FROM Teacher WHERE Email = '$email' AND Password = '$password'";
$teacherResult = $conn->query($teachers);

$students = "SELECT * FROM Student WHERE Email = '$email' AND Password = '$password'";
$studentResult = $conn->query($students);

if($adminResult->num_rows == 1){
	$_SESSION["email"] = $email;
	$_SESSION["password"] = $password;
	header("refresh:0; url=Admin.php");
	die();
}

else if($teacherResult->num_rows == 1){
	$_SESSION["email"] = $email;
	$_SESSION["password"] = $password;
	header("refresh:0; url=Teacher.php");
	die();
}

else if($studentResult->num_rows == 1){
	$_SESSION["email"] = $email;
	$_SESSION["password"] = $password;
	header("refresh:0; url=Student.php");
	die();
}

else{
	header("refresh:0; url=SignInForm.php");
	die();
}

$conn->close();
?> 
</body>
</html> 