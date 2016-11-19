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
<title>Admin</title>
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
	<div class="container">
		<div class="jumbotron">
			<?php echo "<h1>Hello, $name !</h1>"; ?>
		</div>     
	</div>
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

		$submissions = $conn->query("SELECT * FROM Submission")->num_rows;
		$requests = $conn->query("SELECT * FROM Form")->num_rows;
		$admins = $conn->query("SELECT * FROM Admin")->num_rows;
		$teachers = $conn->query("SELECT * FROM Teacher")->num_rows;
		$students = $conn->query("SELECT * FROM Student")->num_rows;

		echo "<br>";
		echo "<div class='container'>";          
		  echo "<table class='table table-bordered'>";
		    echo "<thead>";
		      echo "<tr>";
		        echo "<th>News Feed</th>";
		      echo "</tr>";
		    echo "</thead>";
		    echo "<tbody>";
		      echo "<tr>";
		        echo "<td><a href='SubmissionsViewByAdmin.php'><span class='glyphicon glyphicon-send'></span> All Submissions <span class='badge'>$submissions</span></a><br></td>";
		      echo "</tr>";
		      echo "<tr>";
		        echo "<td><a href='Requests.php'><span class='glyphicon glyphicon-user'></span> Verification Requests <span class='badge'>$requests</span></a><br></td>";
		      echo "</tr>";
		    echo "</tbody>";
		  echo "</table>";
		echo "</div>";

		echo "<br>";
		echo "<div class='container'>";          
		  echo "<table class='table table-bordered'>";
		    echo "<thead>";
		      echo "<tr>";
		        echo "<th>All Users</th>";
		      echo "</tr>";
		    echo "</thead>";
		    echo "<tbody>";
		      echo "<tr>";
		        echo "<td><a href='AdminList.php'><span class='glyphicon glyphicon-user'></span> System Admins <span class='badge'>$admins</span></a><br></td>";
		      echo "</tr>";
		      echo "<tr>";
		        echo "<td><a href='TeacherList.php'><span class='glyphicon glyphicon-user'></span> Honourable Teachers <span class='badge'>$teachers</span></a><br></td>";
		      echo "</tr>";
		       echo "<tr>";
		        echo "<td><a href='StudentList.php'><span class='glyphicon glyphicon-user'></span> All Students <span class='badge'>$students</span></a><br></td>";
		      echo "</tr>";
		    echo "</tbody>";
		  echo "</table>";
		echo "</div>";
		$conn->close();
	?>
</body>
</html>
