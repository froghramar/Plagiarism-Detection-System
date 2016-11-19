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
<html>
<title>List of Students</title>
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
					<li><a href="AdminList.php" title="List of Admins"><span class="glyphicon glyphicon-user"></span> Admins</a></li>
				</ul>
				<ul class="nav navbar-nav">
					<li class="active"><a href="StudentList.php" title="List of Students"><span class="glyphicon glyphicon-user"></span> Students</a></li>
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
					<li><a href="AddStudent.php" title="Create New User Account"><span class="glyphicon glyphicon-plus"></span> Add User</a></li>
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
		$sql = "SELECT * FROM Student";
		$result = $conn->query($sql);
		$id = 0;
		echo "<div class='container'>";
		echo "<h2>List of Students</h2>";
		echo "<div class='panel-group'>";
		while($row = $result->fetch_assoc()){
			$id = $id + 1;
			echo "<div class='panel panel-default'>";
				echo "<div class='panel-heading'>";
					echo "<h4 class='panel-title'>";
					$name = $row["name"];
					$faculty = $row["faculty"];
					$department = $row["department"];
					$class = $row["class"];
					$registration = $row["registration"];
					$roll = $row["roll"];
					$email = $row["email"];
					$session = $row["session"];
					$HREF = "#collapse".$id."";
					$ID = "collapse".$id."";
					echo "<a data-toggle='collapse' href=$HREF>$name</a>";
					echo "</h4>";
				echo "</div>";
				echo "<div id=$ID class='panel-collapse collapse'>";
					echo "<ul class='list-group'>";
					echo "<li class='list-group-item'>Faculty : $faculty</li>";
					echo "<li class='list-group-item'>Department : $department</li>";
					echo "<li class='list-group-item'>Class : $class</li>";
					echo "<li class='list-group-item'>Registration : $registration</li>";
					echo "<li class='list-group-item'>Roll : $roll</li>";
					echo "<li class='list-group-item'>Email : $email</li>";
					echo "<li class='list-group-item'>Session : $session</li>";
					echo "</ul>";
				echo "</div>";
			echo "</div>";
		}
		echo "</div>";
		$conn->close();
	?>
	</div>
</body>
</html>
