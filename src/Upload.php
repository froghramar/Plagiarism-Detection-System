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

$students = "SELECT * FROM Student WHERE Email = '$email' AND Password = '$password'";
$studentResult = $conn->query($students);

if($studentResult->num_rows == 0){
	header("refresh:0; url=Home.php");
	die();
}
$row = $studentResult->fetch_assoc();
$name = $row["name"];
$conn->close();
?>
<html>
<title>My Submissions</title>
<head>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="css/w3.css">
<link rel="stylesheet" type="text/css" href="css/stdtheme.css"/>
</head>
<body>
	<div class='w3-container top'>
		<a class='w3schools-logo' href='Home.php'>PDS<span class='dotcom'>.com</span></a>
		<div class='w3-right toptext w3-wide'>AN UNIQUE SUBMISSION SYSTEM</div>
	</div>
	<nav class="navbar navbar-inverse">
		<div class="container-fluid">
			<div class="collapse navbar-collapse" id="myNavbar">
				<ul class="nav navbar-nav">
					<li><a href="Home.php" title="Home Page"><span class="glyphicon glyphicon-home"></span></a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="Submit.php" title="Submit Your Document"><span class="glyphicon glyphicon-send"></span> Submit</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li class="active"><a href="SubmissionsViewByStudent.php" title="View My Submissions"><span class="glyphicon glyphicon-zoom-in"></span> My Submissions</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<?php echo "<a class='dropdown-toggle' data-toggle='dropdown' href='Student.php'>$name<span class='caret'></span></a>"; ?>
						<ul class="dropdown-menu">
							<?php echo "<li><a href='Student.php' title='View My Profile'><span class='glyphicon glyphicon-user'></span> $name</a></li>"; ?>
							<li><a href="ChangePasswordStudent.php" title="Change Your Password"><span class="glyphicon glyphicon-wrench"></span> Change Password</a></li>
						</ul>
					</li>
					<li><a href="signout.php"><span class="glyphicon glyphicon-log-out"></span> Sign Out</a></li>
				</ul>
			</div>
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

$subid = $conn->query("Select * FROM Submission")->num_rows + 1;

if(isset($_FILES['file'])){
	$file = $_FILES['file'];
	$file_name = $file['name'];
	$file_temp = $file['tmp_name'];
	
	$file_ext = explode('.', $file_name);
	$file_ext = strtolower(end($file_ext));
	$allowed = array('pdf');

	if(in_array($file_ext, $allowed)){
		$file_name_new = $subid.'.'.$file_ext;
		$file_destination = 'Documents/'.$file_name_new;
		if(move_uploaded_file($file_temp, $file_destination)){
			echo "<div class='container'>";
			  echo "<h2>Message</h2>";
			  echo "<div class='alert alert-success'>";
			    echo "<strong>Success!</strong> You have successfuly submitted your document.";
			  echo "</div>";
			  echo "<div class='alert alert-info'>";
			    echo "<strong>Info!</strong> Pleaase wait for a few seconds while we are generating your report.";
			  echo "</div>";
			echo "</div>";
			$email = $_SESSION["email"];
			$SubType = $_POST["SubType"];
			$Course = $_POST["course"];
			$title = $_POST["title"];
			$status = "Pending";
			$sql = "Insert into Submission (SubID, Email, SubType, Course, Title, Status) Values ('$subid', '$email', '$SubType', '$Course', '$title', '$status')";
			$conn->query($sql);
			$url = "CosineSimilarity.php?id=".$subid;
			header("refresh:0; url=$url");
		}
		else{
			echo "An error occured";
		}
	}
}

$conn->close();
?>
</body>
</html> 