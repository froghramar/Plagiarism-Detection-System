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

$teachers = "SELECT * FROM Teacher WHERE Email = '$email' AND Password = '$password'";
$teacherResult = $conn->query($teachers);

if($teacherResult->num_rows == 0){
	header("refresh:0; url=Home.php");
	die();
}
$row = $teacherResult->fetch_assoc();
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
					<li><a href="SubmissionsViewByTeacher.php" title="All Submissions"><span class="glyphicon glyphicon-send"></span> All Submissions</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<?php echo "<a class='dropdown-toggle' data-toggle='dropdown' href='Teacher.php'>$name<span class='caret'></span></a>"; ?>
						<ul class="dropdown-menu">
							<?php echo "<li><a href='Teacher.php' title = 'View Profile'><span class='glyphicon glyphicon-user'></span> $name</a></li>"; ?>
							<li><a href="ChangePasswordTeacher.php"  title="Change Your Password"><span class='glyphicon glyphicon-wrench'></span> Change Password</a></li>
						</ul>
					</li>
					<li><a href="signout.php" title="Sign Out"><span class="glyphicon glyphicon-log-out"></span> Sign Out</a></li>
				</ul>
			</div>
		</div>
	</nav>
	<div class="container">
		<h3>Change Password</h3>
		<form role="form" action="PasswordChange.php" method="post">
			<div class="form-group">
				<label for="current">Current Password:</label>
				<input type="password" required="true" class="form-control" name="current" id="current" placeholder="Enter current password">
			</div>
			<div class="form-group">
				<label for="new">Password:</label>
				<input type="password" required="true" class="form-control" name="new" id="new" placeholder="Enter new password">
			</div>
			<button type="submit" class="btn btn-default">Submit</button>
		</form>
	</div>
</body>
</html>
