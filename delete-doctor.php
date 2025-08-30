<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Delete Doctor</title>
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

    .delete-form {
      background: rgba(0, 0, 0, 0.7);
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(0, 240, 255, 0.5);
      margin-bottom: 30px;
      width: 100%;
      max-width: 500px;
    }

    .delete-form input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border-radius: 8px;
      border: 2px solid #00f0ff;
      background: rgba(0, 0, 0, 0.5);
      color: white;
      font-size: 16px;
    }

    .delete-form input:focus {
      outline: none;
      border-color: #ffffff;
      box-shadow: 0 0 10px #00f0ff;
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
    <h1>Delete Doctor</h1>
    
    <?php
    // Database configuration (consider moving to separate config file)
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "hospitale";
    
    // Create connection
    $con = new mysqli($host, $username, $password, $dbname);
    
    // Check connection
    if($con->connect_error) {
        die("<div class='error-message'>Database connection failed</div>");
    }

    // Process form only if submitted via POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate input
        if (empty($_POST["username"])) {
            echo "<div class='error-message'>Username is required</div>";
        } else {
            $username = trim($_POST["username"]);
            
            // Validate username format
            if (!preg_match("/^[a-zA-Z0-9_]{3,20}$/", $username)) {
                echo "<div class='error-message'>Invalid username format</div>";
            } else {
                // Prepare and bind
                $stmt = $con->prepare("DELETE FROM doctor WHERE username = ?");
                if (!$stmt) {
                    error_log("Prepare failed: " . $con->error);
                    echo "<div class='error-message'>Database error. Please try again.</div>";
                } else {
                    $stmt->bind_param("s", $username);
                    
                    // Execute and check result
                    if ($stmt->execute()) {
                        echo "<div class='success-message'>Doctor deleted successfully!</div>";
                    } else {
                        error_log("Deletion failed: " . $stmt->error);
                        echo "<div class='error-message'>Deletion failed. Please try again.</div>";
                    }
                    $stmt->close();
                }
            }
        }
    }
    
    $con->close();
    ?>

    <div class="delete-form">
      <input type="text" name="username" placeholder="Enter Doctor Username" required>
      <button type="submit" class="btn" onclick="confirmDeletion()">Delete Doctor</button>
    </div>

    <a href="admindashboard.html" class="btn">Back to Admin DashBoard</a>
  </div>

  <script>
    function confirmDeletion() {
      if (confirm('Are you sure you want to delete this doctor? This action cannot be undone.')) {
        // Create form and submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.style.display = 'none';
        
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'username';
        input.value = document.querySelector('.delete-form input').value;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
      }
    }
  </script>
</body>
</html>




