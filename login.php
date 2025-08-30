<?php
// Start a new or resume an existing session.
session_start();

// --- IMPORTANT: Database Connection ---
// Replace these with your actual database credentials.
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospitale";

// Create connection
$con = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($con->connect_error) {
    // Stop the script and display a styled connection error page.
    echo '<!DOCTYPE html><html><head><meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/><title>DocNowLk - Error</title><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/><style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html,body{height:100%;font-family:\'Segoe UI\',sans-serif; overflow-y: auto; }
        #bg-video{position:fixed;inset:0;width:100%;height:100%;object-fit:cover;z-index:-1;opacity:.7;}
        .content{position:relative;min-height:100vh;display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center;background:rgba(0,0,0,.6);color:#fff;padding:20px;}
        h1{font-size:3em;margin-bottom:10px;text-shadow:0 0 10px #00f0ff,0 0 20px #00f0ff,0 0 30px #00f0ff;animation:glow 2s infinite alternate;}
        .error-container{background:rgba(255,100,100,.2);border:2px solid #ff6464;border-radius:20px;padding:40px;margin:20px;max-width:600px;box-shadow:0 0 30px rgba(255,100,100,.3);animation:shake .5s;}
        .error-icon{font-size:4em;color:#ff6464;margin-bottom:20px;text-shadow:0 0 20px #ff6464;}
        .btn{display:inline-flex;align-items:center;gap:8px;padding:12px 28px;font-size:16px;border:none;border-radius:50px;background:#00f0ff;color:#000;text-decoration:none;cursor:pointer;transition:.3s;box-shadow:0 0 10px #00f0ff,0 0 20px #00f0ff;}
        .btn:hover{background:#fff;color:#007BFF;box-shadow:0 0 20px #00f0ff,0 0 40px #00f0ff;}
        @keyframes glow{from{text-shadow:0 0 5px #00f0ff,0 0 10px hsl(184,100%,50%);}to{text-shadow:0 0 15px #00f0ff,0 0 30px #00f0ff;}}
        @keyframes shake{0%,100%{transform:translateX(0);}10%,30%,50%,70%,90%{transform:translateX(-5px);}20%,40%,60%,80%{transform:translateX(5px);}}
    </style></head><body><video autoplay muted loop playsinline id="bg-video"><source src="https://videos.pexels.com/video-files/7195621/7195621-uhd_2732_1440_25fps.mp4" type="video/mp4"/></video><div class="content"><h1>DocNowLk</h1><p>Your Health, Our Priority</p><div class="error-container"><i class="fas fa-exclamation-triangle error-icon"></i><h2>Connection Error</h2><p>Database connection failed: ' . $con->connect_error . '</p><a href="login.html" class="btn"><i class="fas fa-redo"></i> Try Again</a></div></div></body></html>';
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DocNowLk</title>
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* Base styles */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body {
            height: 100%;
            font-family: 'Segoe UI', sans-serif;
            background-color: #0d1117;
            color: #e6edf3;
            overflow: hidden;
        }
        /* Video background */
        #bg-video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
            opacity: 0.3;
        }
        /* Main content container */
        .content {
            position: relative;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            background: rgba(0, 0, 0, 0.6);
            padding: 20px;
        }
        /* Heading styles */
        h1 {
            font-size: 3em;
            margin-bottom: 10px;
            text-shadow: 0 0 10px #00f0ff, 0 0 20px #00f0ff;
            animation: glow 2s infinite alternate;
        }
        p {
            font-size: 1.2em;
            color: #d9faff;
        }
        /* Error container */
        .error-container {
            background: rgba(255, 100, 100, 0.2);
            border: 2px solid #ff6464;
            border-radius: 20px;
            padding: 40px;
            margin: 20px;
            max-width: 600px;
            box-shadow: 0 0 30px rgba(255, 100, 100, 0.3);
            animation: shake 0.5s;
        }
        .error-icon {
            font-size: 4em;
            color: #ff6464;
            margin-bottom: 20px;
            text-shadow: 0 0 20px #ff6464;
        }
        /* Button styles */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            font-size: 16px;
            border: none;
            border-radius: 50px;
            background: #00f0ff;
            color: #000;
            text-decoration: none;
            cursor: pointer;
            transition: 0.3s;
            box-shadow: 0 0 10px #00f0ff, 0 0 20px #00f0ff;
            margin-top: 20px;
        }
        .btn:hover {
            background: #fff;
            color: #007BFF;
            box-shadow: 0 0 20px #00f0ff, 0 0 40px #00f0ff;
        }
        /* Keyframe animations */
        @keyframes glow {
            from { text-shadow: 0 0 5px #00f0ff, 0 0 10px hsl(184, 100%, 50%); }
            to { text-shadow: 0 0 15px #00f0ff, 0 0 30px #00f0ff; }
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
    </style>
</head>
<body>
    <video autoplay muted loop playsinline id="bg-video">
        <source src="https://videos.pexels.com/video-files/7195621/7195621-uhd_2732_1440_25fps.mp4" type="video/mp4" />
    </video>
    <div class="content">

<?php
// --- Login Logic ---
// Check if the form was submitted using POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user inputs
    $username = $con->real_escape_string($_POST["username"]);
    $password = $_POST["pass"];

    // First, check for the hardcoded admin user
    if ($username === "admin" && $password === "1234") {
        header("Location: admindashboard.html");
        exit();
    }

    // Check the database for a doctor
    $stmt_doctor = $con->prepare("SELECT username, password, doctorname FROM doctor WHERE username = ?");
    $doctor_found = false;
    if ($stmt_doctor) {
        $stmt_doctor->bind_param("s", $username);
        $stmt_doctor->execute();
        $res_doctor = $stmt_doctor->get_result();

        if ($user_doctor = $res_doctor->fetch_assoc()) {
            if ($password === $user_doctor['password']) {
                // If a doctor is found and the password matches, set session variables.
                $_SESSION['username'] = $user_doctor['username'];
                $_SESSION['userfname'] = $user_doctor['doctorname'];
                $doctor_found = true;
                header("Location: logindoc.php");
                exit();
            }
        }
        $stmt_doctor->close();
    }

    // If not a doctor, check the database for a patient
    $stmt_patient = $con->prepare("SELECT username, password, fname FROM patient WHERE username = ?");
    $patient_found = false;
    if ($stmt_patient) {
        $stmt_patient->bind_param("s", $username);
        $stmt_patient->execute();
        $res_patient = $stmt_patient->get_result();

        if ($user_patient = $res_patient->fetch_assoc()) {
            if ($password === $user_patient['password']) {
                $_SESSION['username'] = $user_patient['username'];
                $_SESSION['userfname'] = $user_patient['fname'];
                $patient_found = true;
                header("Location: loginpatient.php");
                exit();
            }
        }
        $stmt_patient->close();
    }

    // If none of the above logins were successful, display the styled error message.
    if (!$doctor_found && !$patient_found) {
        echo '<div class="error-container">
                <i class="fas fa-user-times error-icon"></i>
                <h2>Account Not Found</h2>
                <p>Please check your username and password.</p>
                <a href="login.html" class="btn"><i class="fas fa-redo"></i> Try Again</a>
            </div>';
    }

} else {
    // If someone tries to access this page directly
    header("Location: login.html");
    exit();
}

// Close the database connection.
$con->close();
?>
    </div> <!-- end of .content -->
</body>
</html>
