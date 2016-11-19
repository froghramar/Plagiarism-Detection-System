<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?php
		session_start();
		session_unset(); 
		session_destroy();
		header("refresh:0; url='Home.php'");
	?>
</body>
</html>