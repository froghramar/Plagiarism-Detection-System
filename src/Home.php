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

$user = "known";

if(isset($_SESSION["email"]) == FALSE || isset($_SESSION["password"]) == FALSE){
  if(isset($_POST["email"]) == FALSE || isset($_POST["password"]) == FALSE){
    $user = "unknown";
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

if($user == "known"){
  $admins = "SELECT * FROM Admin WHERE Email = '$email' AND Password = '$password'";
  $adminResult = $conn->query($admins);

  $teachers = "SELECT * FROM Teacher WHERE Email = '$email' AND Password = '$password'";
  $teacherResult = $conn->query($teachers);

  $students = "SELECT * FROM Student WHERE Email = '$email' AND Password = '$password'";
  $studentResult = $conn->query($students);

  if($adminResult->num_rows == 1){
    $_SESSION["email"] = $email;
    $_SESSION["password"] = $password;
    $user = 'admin';
    $row = $adminResult->fetch_assoc();
    $name = $row["name"];
  }

  else if($teacherResult->num_rows == 1){
    $_SESSION["email"] = $email;
    $_SESSION["password"] = $password;
    $user = 'teacher';
    $row = $teacherResult->fetch_assoc();
    $name = $row["name"];
  }

  else if($studentResult->num_rows == 1){
    $_SESSION["email"] = $email;
    $_SESSION["password"] = $password;
    $user = 'student';
    $row = $studentResult->fetch_assoc();
    $name = $row["name"];
  }

  else{
    $user = 'unknown';
  } 
}
$conn->close();
?> 
<!DOCTYPE html>
<html lang="en">
<head>
  <title>PDS Home</title>
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
  <div class="container-fluid">
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li class="active"><a href="Home.php" title="Home Page"><span class="glyphicon glyphicon-home"></span></a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <?php
            if($user == 'unknown'){
                echo "<li><a href='SignUpForm.php'><span class='glyphicon glyphicon-user'></span> Sign Up</a></li>";
                echo "<li><a href='signin.php'><span class='glyphicon glyphicon-log-in'></span> Sign In</a></li>";
            }
            else if($user == 'admin'){
                echo "<li class='dropdown'>";
                  echo "<a class='dropdown-toggle' data-toggle='dropdown' href='Admin.php'>$name<span class='caret'></span></a>";
                  echo "<ul class='dropdown-menu'>";
                    echo "<li><a href='Admin.php' title='View Profile'><span class='glyphicon glyphicon-user'></span> $name</a></li>";
                    echo "<li><a href='ChangePassword.php' title='Change Your Password'><span class='glyphicon glyphicon-wrench'></span> Change Password</a></li>";
                  echo "</ul>";
                echo "</li>";
                echo "<li><a href='signout.php' title='Sign Out'><span class='glyphicon glyphicon-log-out'></span> Sign Out</a></li>";
            }
            else if($user == 'teacher'){
                echo "<li class='dropdown'>";
                  echo "<a class='dropdown-toggle' data-toggle='dropdown' href='Teacher.php'>$name<span class='caret'></span></a>";
                  echo "<ul class='dropdown-menu'>";
                    echo "<li><a href='Teacher.php' title='View Profile'><span class='glyphicon glyphicon-user'></span> $name</a></li>";
                    echo "<li><a href='ChangePasswordTeacher.php' title='Change Your Password'><span class='glyphicon glyphicon-wrench'></span> Change Password</a></li>";
                  echo "</ul>";
                echo "</li>";
                echo "<li><a href='signout.php' title='Sign Out'><span class='glyphicon glyphicon-log-out'></span> Sign Out</a></li>";
            }
            else if($user == 'student'){
                echo "<li class='dropdown'>";
                  echo "<a class='dropdown-toggle' data-toggle='dropdown' href='Student.php'>$name<span class='caret'></span></a>";
                  echo "<ul class='dropdown-menu'>";
                    echo "<li><a href='Student.php' title='View Profile'><span class='glyphicon glyphicon-user'></span> $name</a></li>";
                    echo "<li><a href='ChangePasswordStudent.php' title='Change Your Password'><span class='glyphicon glyphicon-wrench'></span> Change Password</a></li>";
                  echo "</ul>";
                echo "</li>";
                echo "<li><a href='signout.php' title='Sign Out'><span class='glyphicon glyphicon-log-out'></span> Sign Out</a></li>";
            }
        ?>
      </ul>
    </div>
  </div>
</nav>

<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
      <div class="item active">
        <img src="/images/img1.jpg" alt="Image">
        <div class="carousel-caption">
          <h3>We Offer You An Online Document Submission System</h3>
        </div>      
      </div>

      <div class="item">
        <img src="/images/img2.jpg" alt="Image">
        <div class="carousel-caption">
          <h3>We Detect Plagiarism In Your Documents</h3>
        </div>      
      </div>
    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
</div>

<footer class="container-fluid text-center">
  <p>copyright &copy; Feroz Ahmmed, 2016</a></p>
</footer>

</body>
</html>
