<!DOCTYPE html>
<html >
<head>
	<meta charset="UTF-8">
	<title>Sign-Up/Login Form</title>
	<link rel="icon" href="/favicon.ico" type="image/x-icon">
  	<link rel="stylesheet" href="css/w3.css">
  	<link rel="stylesheet" type="text/css" href="css/stdtheme.css"/>
	<link href='css/first.css' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="css/normalize.css">
	<link rel="stylesheet" href="css/style.css">
</head>

<body>

<nav class="navbar navbar-inverse">
   <div class='w3-container top'>
    <a class='w3schools-logo' href='Home.php'>PDS<span class='dotcom'>.com</span></a>
    <div class='w3-right toptext w3-wide'>AN UNIQUE SUBMISSION SYSTEM</div>
  </div>
</nav>

<div class="form">
<ul class="tab-group">
<li class="tab active"><a href="#signup">Sign Up</a></li>
<li class="tab"><a href="#login">Log In</a></li>

</ul>
<div class="tab-content">
	<div id="signup">   
		<h1>Sign Up for Free</h1>
		<form action="signup.php" method="post">
			<div class="field-wrap">
				<label>Name<span class="req">*</span> </label>
				<input type="text" required autocomplete="off" name="name" id="name" />
			</div>
			<div class="field-wrap">
            	<label for="faculty"><span class="glyphicon glyphicon-flag"></span></label>
                <select class="form-control" name="faculty" id="faculty" style="background-color: rgba(19, 35, 47, 0.9); color: white; font-size: 22px; width: 100%; height: 100%; padding: 5px 10px">
                    <option>Science</option>
                    <option>Arts</option>
                    <option>Commerce</option>
                </select>  
          </div>
			<div class="field-wrap">
				<label>Department<span class="req">*</span> </label>
				<input type="text" required autocomplete="off" name="department" id="department" />
			</div>
			<div class="field-wrap">
            	<label for="class"><span class="glyphicon glyphicon-flag"></span></label>
                <select class="form-control" name="class" id="class" style="background-color: rgba(19, 35, 47, 0.9); color: white; font-size: 22px; width: 100%; height: 100%; padding: 5px 10px;">
                    <option>Honars 1st Year</option>
                    <option>Honars 2nd Year</option>
                    <option>Honars 3rd Year</option>
                    <option>Honars 4th Year</option>
                    <option>Masters 1st Year</option>
                    <option>Masters 2nd Year</option>
                </select>  
          </div>
			<div class="field-wrap">
				<label>Registration Number<span class="req">*</span> </label>
				<input type="text" required autocomplete="off" name="registration_no" id="registration_no" />
			</div>
			<div class="field-wrap">
				<label>Class Roll<span class="req">*</span> </label>
				<input type="number" required autocomplete="off" name="roll" id="roll" />
			</div>
			<div class="field-wrap">
				<label>Email Address<span class="req">*</span></label>
				<input type="email"required autocomplete="off" name="email" id="email" />
			</div>
			<div class="field-wrap">
				<label>Session<span class="req">*</span> </label>
				<input type="text" required autocomplete="off" name="session" id="session" />
			</div>
			<div class="field-wrap">
				<label>Set A Password<span class="req">*</span></label>
				<input type="password"required autocomplete="off" name="password" id="password" />
			</div>
			<button type="submit" class="button button-block"/>Get Started</button>
		</form>
	</div>
	<div id="login">   
		<h1>Welcome Back!</h1>
		<form action="signin.php" method="post">
			<div class="field-wrap">
				<label>Email<span class="req">*</span></label>
				<input type="email"required autocomplete="off" name="email" id="email" />
			</div>
			<div class="field-wrap">
				<label>Password<span class="req">*</span></label>
				<input type="password"required autocomplete="off" name="password" id="password"/>
			</div>
			<p class="forgot"><a href="#">Forgot Password?</a></p>
			<button class="button button-block"/>Log In</button>
		</form>
	</div>
</div>
</div>
<script src="js/min.js"></script>
<script src="js/index.js"></script>  
</body>
</html>
