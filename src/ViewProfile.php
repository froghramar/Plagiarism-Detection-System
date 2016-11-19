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

  $email = $_GET["email"];
  $sql = "Select * FROM Student WHERE Email = '$email'";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $name = $row['name'];
  $faculty = $row['faculty'];
  $department = $row['department'];
  $class = $row['class'];
  $registration = $row['registration'];
  $roll = $row['roll'];
  $session = $row['session'];

  $all = $conn->query("SELECT * FROM Submission WHERE Email = '$email'")->num_rows;
  $accepted = $conn->query("SELECT * FROM Submission WHERE Email = '$email' AND Status = 'Accepted'")->num_rows;
  $rejected = $conn->query("SELECT * FROM Submission WHERE Email = '$email'  AND Status = 'Rejected'")->num_rows;
  $considerable = $conn->query("SELECT * FROM Submission WHERE Email = '$email' AND Status = 'Considerable'")->num_rows;
  $accuracy = intval(($accepted + $considerable * 0.5) * 100 / $all);

  $conn->close();

?>
<!DOCTYPE html>
  <html>
    <head>  
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
      <?php echo "<title>$name</title>"; ?>
      <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
      <link href="css/font-awesome.css" rel="stylesheet">
      <link rel="stylesheet" href="css/owl.carousel.css">
      <link id="switcher" href="css/default-theme.css" type="text/css" rel="stylesheet" media="screen,projection"/>     
      <link href="css/Nstyle.css" type="text/css" rel="stylesheet" media="screen,projection"/>
      <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
      <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
      <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
      <link rel="icon" href="/favicon.ico" type="image/x-icon">
      <link rel="stylesheet" href="css/w3.css">
      <link rel="stylesheet" type="text/css" href="css/stdtheme.css"/>
      <style type="text/css">
         #graph{
            position:relative;
            width:360px;
            height:330px;
            margin:8px;
            padding:0;  
        }
        #graph ul{
            position:absolute;
            top:0;
            left:32px;
            width:300px;
            height:300px;
            border-left:1px solid black;
            border-bottom:1px solid black;  
        }
        #graph li{
           position:absolute; 
           list-style:none;
           background:lightblue;
           width:40px;
           text-align:center;
           border:1px solid black;
           visibility: hidden;
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
      <div class="main-wrapper">
        <main role="main">
          <section id="home">
            <div class="overlay-section">
              <div class="container">
                <div class="row">
                  <div class="col s12">
                    <div class="home-inner">
                      <?php echo"<h1 class='home-title'><span>$name</span>'s Profile</h1>"; ?>                 
                    </div>
                  </div>  
                </div>
              </div>
            </div>
          </section>
          <section id="about">
            <div class="container">
              <div class="row">
                <div class="col s12">
                  <div class="about-inner">
                    <div class="row">
                      <div class="col s12 m4 l3">
                        <div class="about-inner-left">
                          <img class="profile-img" src="img/profile.jpg" alt="Profile Image">
                        </div>
                      </div>
                      <div class="col s12 m8 l9">
                        <div class="about-inner-right">
                          <div class="personal-information col s12 m12 l6">
                            <h3>Personal Information</h3>
                            <ul>
                              <?php echo"<li><span>Name : </span>$name</li>"; ?>
                              <?php echo"<li><span>Faculty : </span>$faculty</li>"; ?>
                              <?php echo"<li><span>Department : </span>$department</li>"; ?>
                              <?php echo"<li><span>Class : </span>$class</li>"; ?>
                              <?php echo"<li><span>Registration : </span>$registration</li>"; ?>
                              <?php echo"<li><span>Roll : </span>$roll</li>"; ?>
                              <?php echo"<li><span>Email : </span>$email</li>"; ?>
                              <?php echo"<li><span>Session : </span>$session</li>"; ?>
                            </ul>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
          <section id="about">
            <div class="container">
              <div class="row">
                <div class="col s12">
                  <div class="about-inner">
                    <div class="row">
                      <div class="col s12 m4 l3">
                        <div class="about-inner-left">
                          <h5 style="color: #9900cc">Accuracy</h5>
                          <p><?php echo $accuracy?>%</p>
                        </div>
                      </div>
                      <div class="col s12 m8 l9">
                        <div class="about-inner-right">
                          <div class="personal-information col s12 m12 l6">
                            <h3>Submission History</h3>
                            <div id="graph">
                              <ul>  
                                <li><?php echo $all ?>: All:lightblue</li>
                                <li><?php echo $accepted ?>: Accepted:lightgreen</li>
                                <li><?php echo $rejected ?>:Rejected:yellow</li>
                                <li><?php echo $considerable ?>:Considerable:cyan</li>
                              </ul>
                            </div>
                            <div id="labels"> Verdicts : </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
    </body>
    <script type="text/javascript">
    function makeGraph(container, labels){
        container = document.getElementById(container);
        labels = document.getElementById(labels)
        var dnl = container.getElementsByTagName("li");
        for(var i = 0; i < dnl.length; i++){
            var item = dnl.item(i);
            var value = item.innerHTML;
            var color = item.style.background=color;
            var content = value.split(":");
            value = content[0] * 10;
            item.style.height=value + "px";
            item.style.top=(299 - value) + "px";
            item.style.left = (i * 70 + 20) + "px";
            item.style.height = value + "px";
            item.innerHTML = value / 10;
            item.style.visibility="visible";  
            color = content[2];
            if(color != false) item.style.background=color;
            labels.innerHTML = labels.innerHTML +
                 "<span style='margin:8px;background:"+ color+"'>" + 
                 content[1] + "</span>";
        } 
    }
    window.onload=function () { makeGraph("graph", "labels") }
    </script>
  </html>