
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Doctor</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    html, body {
      height: 100%;
      font-family: 'Segoe UI', sans-serif;
    }

    #bg-video {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: -1;
    }

    .content {
      position: relative;
      height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      background-color: rgba(0, 0, 0, 0.5);
      color: white;
      padding: 20px;
    }

    h1 {
      font-size: 3em;
      margin-bottom: 20px;
      text-shadow: 0 0 10px #00f0ff, 0 0 20px #00f0ff, 0 0 30px #00f0ff;
      animation: glow 2s infinite alternate;
    }

    .success-message {
      font-size: 1.8em;
      margin-bottom: 30px;
      color: #00ff9d;
      text-shadow: 0 0 5px #00ff9d;
      animation: pulse 1.5s infinite;
    }

    .error-message {
      font-size: 1.8em;
      margin-bottom: 30px;
      color: #ff3366;
      text-shadow: 0 0 5px #ff3366;
    }

    .btn {
      display: inline-block;
      margin: 10px;
      padding: 12px 28px;
      font-size: 16px;
      border: none;
      border-radius: 50px;
      background-color: #00f0ff;
      color: #000;
      text-decoration: none;
      transition: 0.3s;
      box-shadow: 0 0 10px #00f0ff, 0 0 20px #00f0ff;
      cursor: pointer;
    }

    .btn:hover {
      background-color: #ffffff;
      color: #007BFF;
      box-shadow: 0 0 20px #00f0ff, 0 0 40px #00f0ff;
    }

    @keyframes glow {
      from {
        text-shadow: 0 0 5px #00f0ff, 0 0 10px #00f0ff;
      }
      to {
        text-shadow: 0 0 15px #00f0ff, 0 0 30px #00f0ff;
      }
    }

    @keyframes pulse {
      0% { transform: scale(1); }
      50% { transform: scale(1.05); }
      100% { transform: scale(1); }
    }
  </style>
</head>
<body>
  <video autoplay muted loop playsinline id="bg-video">
    <source src="doctor.mp4" type="video/mp4">
  </video>

  <div class="content">
    <h1>Doctor</h1>

    <?php
    $con = new mysqli("localhost","root","","hospitale");
    if($con->connect_error) {
        die("<div class='error-message'>Database connection failed</div>");
    }

    $UNAME= $_POST["username"];
    $PASS = $_POST["newpassword"];
    $DNAME = $_POST["name"];
    $SPZ = $_POST["specialization"];
    $DATE= $_POST["date"];
    $TIME = $_POST["time"];
    $EMAIL = $_POST["email"];
    $TEL = $_POST["telephone"];

    $sql = "UPDATE doctor SET Password ='$PASS',DoctorName ='$DNAME',
    Specialization = '$SPZ',Date ='$DATE',time ='$TIME',Email='$EMAIL',telephone='$TEL' WHERE username = '$UNAME' ";
    if($con->query($sql) === TRUE) {
        echo "<div class='success-message'>Details Change Successfully!</div>";
    } else {
        echo "<div class='error-message'>Error: " . $con->error . "</div>";
    }

    $con->close();
    ?>
    <a href="admindashboard.html" class="btn">Back to Admindashboard</a>
  </div>
</body>
</html>
