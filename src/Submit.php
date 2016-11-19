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
<title>Student</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
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
					<li class="active"><a href="Submit.php" title="Submit Your Document"><span class="glyphicon glyphicon-send"></span> Submit</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="SubmissionsViewByStudent.php" title="View My Submissions"><span class="glyphicon glyphicon-zoom-in"></span> My Submissions</a></li>
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
	<div class="container">
		<h3>Submit Your Document</h3>
		<form action="Upload.php" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label for="SubType">Submission Type:</label>
				<select class="form-control" name="SubType" id="SubType">
					<option>Assignment</option>
					<option>Class Test</option>
					<option>Project Document</option>
					<option>Research Paper</option>
					<option>Other</option>
				</select>  
			</div>
			<div class="form-group">
				<label for="course">Course:</label>
				<select class="form-control" name="course" id="course">
					<option>CSE 501</option>
					<option>CSE 502</option>
					<option>BUS 503</option>
					<option>CSE 504</option>
					<option>SE 505</option>
					<option>SE 506</option>
				</select>  
			</div>
			<div class="form-group">
				<label for="title">Document Title:</label>
				<input type="text" required="true" class="form-control" name="title" id="title" placeholder="Enter document title">
			</div>
			<div class="form-group">
				<label for="file">Select a File:</label>
				<input type="file" required="true" class="form-control" name="file" id="file">
			</div>
			<button type="submit" class="btn btn-default">Submit</button>
		</form>
	</div>
</body>
</html> 