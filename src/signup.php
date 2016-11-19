<!DOCTYPE html>
<html lang="en">
<head>
  <title>Sign Up</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <link rel="icon" href="/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="css/w3.css">
  <link rel="stylesheet" type="text/css" href="css/stdtheme.css"/>
  <style>
    /* Remove the navbar's default margin-bottom and rounded borders */ 
    .navbar {
      margin-bottom: 0;
      border-radius: 0;
    }
    /* Add a gray background color and some padding to the footer */
    footer {
      background-color: #f2f2f2;
      padding: 25px;
    }
  .carousel-inner img {
      width: 100%; /* Set width to 100% */
      margin: auto;
      min-height:200px;
  }
  /* Hide the carousel text when the screen is less than 600 pixels wide */
  @media (max-width: 600px) {
    .carousel-caption {
      display: none; 
    }
  }
  </style>
</head>
<body>

<nav class="navbar navbar-inverse">
   <div class='w3-container top'>
    <a class='w3schools-logo' href='Home.php'>PDS<span class='dotcom'>.com</span></a>
    <div class='w3-right toptext w3-wide'>AN UNIQUE SUBMISSION SYSTEM</div>
  </div>
</nav>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "PDS";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	echo "Connection Failed ";
    die("Connection failed: " . $conn->connect_error);
}

$name = mysqli_real_escape_string($conn, $_POST['name']);
$faculty = mysqli_real_escape_string($conn, $_POST['faculty']);
$department = mysqli_real_escape_string($conn, $_POST['department']);
$class = mysqli_real_escape_string($conn, $_POST['class']);
$registration_no = mysqli_real_escape_string($conn, $_POST['registration_no']);
$roll = mysqli_real_escape_string($conn, $_POST['roll']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$session = mysqli_real_escape_string($conn, $_POST['session']);
$password = mysqli_real_escape_string($conn, $_POST['password']);

$students = "SELECT * FROM Student WHERE Email = '$email'";
$teachers = "SELECT * FROM Teacher WHERE Email = '$email'";
$admins = "SELECT * FROM Admin WHERE Email = '$email'";
$forms = "SELECT * FROM Form WHERE Email = '$email'";

$studentResult = $conn->query($students);
$teacherResult = $conn->query($teachers);
$adminResult = $conn->query($admins);
$formResult = $conn->query($forms);

if($studentResult->num_rows > 0 || $teacherResult->num_rows > 0 || $adminResult->num_rows > 0 || $formResult->num_rows > 0){
	echo "<div class='container'>";
	  echo "<h2>Message</h2>";
	  echo "<div class='alert alert-warning'>";
	    echo "<strong>Warning!</strong> The email you provided is already used by another user.";
	  echo "</div>";
	  echo "<div class='alert alert-info'>";
	    echo "<strong>Info!</strong> Please sign up with another email address.";
	  echo "</div>";
	echo "</div>";
}
else{
	$sql = "INSERT INTO Form (name, faculty, department, class, registration, roll, email, session, password)
	VALUES ('$name', '$faculty', '$department', '$class', '$registration_no', '$roll', '$email', '$session', '$password')";

	if($conn->query($sql) === TRUE) {
	    echo "<div class='container'>";
		  echo "<h2>Message</h2>";
		  echo "<div class='alert alert-success'>";
		    echo "<strong>Success!</strong> You have successfully signed up.";
		  echo "</div>";
		  echo "<div class='alert alert-info'>";
		    echo "<strong>Info!</strong> Please wait untill admin verifies your account.";
		  echo "</div>";
		echo "</div>";
	}
	else {
	    echo "<div class='container'>";
		  echo "<h2>Message</h2>";
		  echo "<div class='alert alert-warning'>";
		    echo "<strong>Warning!</strong> An error occured while processing your request.";
		  echo "</div>";
		  echo "<div class='alert alert-info'>";
		    echo "<strong>Info!</strong> We suggest you to try again.";
		  echo "</div>";
		echo "</div>";
	}
}

$conn->close();
?> 
</body>
</html> 