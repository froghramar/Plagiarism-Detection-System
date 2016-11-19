<html>
<title>Admin</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="css/w3.css">
<link rel="stylesheet" type="text/css" href="css/stdtheme.css"/>
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

$students = "SELECT * FROM Admin WHERE Email = '$email' AND Password = '$password'";
$studentResult = $conn->query($students);

if($studentResult->num_rows == 0){
	header("refresh:0; url=Home.php");
	die();
}
$row = $studentResult->fetch_assoc();
$name = $row["name"];
$conn->close();
?>
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
					<li><a href="AdminList.php" title="List of Admins"><span class="glyphicon glyphicon-user"></span> Admins</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="StudentList.php" title="List of Students"><span class="glyphicon glyphicon-user"></span> Students</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="TeacherList.php" title="List of Teachers"><span class="glyphicon glyphicon-user"></span> Teachers</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="SubmissionsViewByAdmin.php" title="All Submissions"><span class="glyphicon glyphicon-send"></span> Submissions</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="Requests.php" title="All Sign Up Requests"><span class="glyphicon glyphicon-user"></span> Requests</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li class="active"><a href="AddStudent.php" title="Create New User Account"><span class="glyphicon glyphicon-plus"></span> Add User</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li><a href="RemoveUser.php" title="Remove An User Account"><span class="glyphicon glyphicon-trash"></span> Remove User</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown">
						<?php echo "<a class='dropdown-toggle' data-toggle='dropdown' href='Admin.php'>$name<span class='caret'></span></a>"; ?>
						<ul class="dropdown-menu">
							<?php echo "<li><a href='Admin.php' title='View Profile'><span class='glyphicon glyphicon-user'></span> $name</a></li>"; ?>
							<li><a href="ChangePassword.php" title="Change Your Password"><span class='glyphicon glyphicon-wrench'></span> Change Password</a></li>
						</ul>
					</li>
					<li><a href="signout.php" title="Sign Out"><span class="glyphicon glyphicon-log-out"></span> Sign Out</a></li>
				</ul>
			</div>
		</div>
	</nav>
	<div class="container">
		<h3>Add a Student Account</h3>
		<ul class="nav nav-pills">
			<li class="active"><a href="AddStudent.php">Student</a></li>
			<li><a href="AddTeacher.php">Teacher</a></li>
			<li><a href="AddAdmin.php">Admin</a></li>
		</ul>
	</div>
	<div class="container">
		<form role="form" action="StudentAddition.php" method="post">
			<div class="form-group">
				<label for="name">Name:</label>
				<input type="text" required="true" class="form-control" name="name" id="name" placeholder="Enter name">
			</div>
			<div class="form-group">
				<label for="faculty">Faculty:</label>
				<select class="form-control" name="faculty" id="faculty">
					<option>Science</option>
					<option>Arts</option>
					<option>Commerce</option>
				</select>  
			</div>
			<div class="form-group">
				<label for="department">Department:</label>
				<input type="text" required="true" class="form-control" name="department" id="department" placeholder="Enter department name">
			</div>
			<div class="form-group">
				<label for="class">Class:</label>
				<select class="form-control" name="class" id="class">
					<option>Honars 1st Year</option>
                    <option>Honars 2nd Year</option>
                    <option>Honars 3rd Year</option>
                    <option>Honars 4th Year</option>
                    <option>Masters 1st Year</option>
                    <option>Masters 2nd Year</option>
				</select>  
			</div>
			<div class="form-group">
				<label for="registration">Registration Number:</label>
				<input type="text" required="true" class="form-control" name="registration" id="registration" placeholder="Enter registration number">
			</div>
			<div class="form-group">
				<label for="roll">Roll:</label>
				<input type="number" required="true" class="form-control" name="roll" id="roll" placeholder="Enter roll number">
			</div>
			<div class="form-group">
				<label for="email">Email:</label>
				<input type="email" required="true" class="form-control" name="email" id="email" placeholder="Enter email">
			</div>
			<div class="form-group">
				<label for="session">Session:</label>
				<input type="text" required="true" class="form-control" name="session" id="session" placeholder="Enter session">
			</div>
			<div class="form-group">
				<label for="password">Password:</label>
				<input type="password" required="true" class="form-control" name="password" id="password" placeholder="Enter password">
			</div>
			<button type="submit" class="btn btn-default">Submit</button>
		</form>
	</div>
</body>
</html>
