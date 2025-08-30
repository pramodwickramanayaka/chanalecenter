<?php
// Start session at the very beginning
session_start();

// Database connection
$con = new mysqli("localhost", "root", "", "hospitale");
if ($con->connect_error) {
    // If connection fails, display an error and exit.
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
    </style></head><body><video autoplay muted loop playsinline id="bg-video"><source src="https://videos.pexels.com/video-files/7195621/7195621-uhd_2732_1440_25fps.mp4" type="video/mp4"/></video><div class="content"><h1>DocNowLk</h1><p>Your Health, Our Priority</p><div class="error-container"><i class="fas fa-exclamation-triangle error-icon"></i><h2>Connection Error</h2><p>Database connection failed: ' . $con->connect_error . '</p><a href="login.html" class="btn"><i class="fas fa-redo"></i> Back to Login</a></div></div></body></html>';
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>DocNowLk - Login Status</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <style>
        /* --- BASE STYLES --- */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html,body{
            height:100%;
            font-family:'Segoe UI',sans-serif;
            overflow-y: auto;
        }
        /* --- VIDEO BACKGROUND --- */
        #bg-video{position:fixed;inset:0;width:100%;height:100%;object-fit:cover;z-index:-1;opacity:.7;}
        /* --- LOGOUT BUTTON CONTAINER --- */
        .logout-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000; /* Ensure it stays on top of other content */
        }
        /* --- CONTENT CONTAINER --- */
        .content{
            position:relative;
            min-height:100vh;
            display:flex;flex-direction:column;
            justify-content:center;align-items:center;text-align:center;
            background:rgba(0,0,0,.6);color:#fff;padding:20px;
        }
        /* --- HEADINGS --- */
        h1{font-size:3em;margin-bottom:10px;text-shadow:0 0 10px #00f0ff,
            0 0 20px #00f0ff,0 0 30px #00f0ff;animation:glow 2s infinite alternate;}
        p{font-size:1.2em;margin-bottom:30px;color:#d9faff;}
        /* --- BUTTON STYLES --- */
        .btn{
            display:inline-flex;align-items:center;gap:8px;
            padding:12px 28px;font-size:16px;border:none;border-radius:50px;
            background:#00f0ff;color:#000;text-decoration:none;cursor:pointer;
            transition:.3s;box-shadow:0 0 10px #00f0ff,0 0 20px #00f0ff;
        }
        .btn:hover{background:#fff;color:#007BFF;box-shadow:0 0 20px #00f0ff,0 0 40px #00f0ff;}
        .about-btn{background:rgba(0,240,255,.3);color:#fff;border:1px solid #00f0ff;}
        .about-btn:hover{background:rgba(0,240,255,.5);}
        /* --- SUCCESS / ERROR BOXES --- */
        .success-container,.error-container, .info-container{
            border-radius:20px;padding:40px;margin:20px;max-width:600px;
            box-shadow:0 0 30px rgba(0,240,255,.3);animation:pulse 3s infinite;
        }
        .success-container{background:rgba(0,240,255,.2);border:2px solid #00f0ff;}
        .error-container{background:rgba(255,100,100,.2);border:2px solid #ff6464;animation:shake .5s;}
        .info-container{background:rgba(255, 255, 255, 0.1); border: 2px solid #fff;}
        .success-icon{font-size:4em;color:#00f0ff;margin-bottom:20px;
            text-shadow:0 0 20px #00f0ff;animation:bounce 2s infinite;}
        .error-icon{font-size:4em;color:#ff6464;margin-bottom:20px;text-shadow:0 0 20px #ff6464;}
        /* --- PROGRESS BAR --- */
        .progress-bar{width:100%;height:4px;background:rgba(255,255,255,.2);
            border-radius:2px;margin:20px 0;overflow:hidden;}
        .progress-fill{height:100%;background:linear-gradient(90deg,#00f0ff,#007BFF);
            border-radius:2px;animation:progress 3s ease-in-out;}
        /* --- HEADING + BUTTON ROW --- */
        .top-row{
            display:flex;justify-content:space-between;align-items:center;
            flex-wrap:wrap;gap:10px;margin-top:30px;
            width: 90%;max-width: 600px;
        }
        .section-title{
            font-size:2.1em;color:#00f0ff;text-shadow:0 0 8px #00f0ff;
            animation:glow 2s infinite alternate;
        }
        /* --- APPOINTMENT TABLE STYLES --- */
        .table-container {
            width: 90%;
            max-width: 600px;
            margin-top: 20px;
            margin-bottom: 40px;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: rgba(0, 0, 0, 0.3);
            box-shadow: 0 0 15px rgba(0, 240, 255, 0.3);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid rgba(0, 240, 255, 0.3);
        }
        th {
            background-color: rgba(0, 240, 255, 0.2);
            color: #00f0ff;
            text-shadow: 0 0 5px #00f0ff;
            position: sticky;
            top: 0;
            z-index: 1;
        }
        tr:hover {
            background-color: rgba(0, 240, 255, 0.1);
        }
        /* Last row border removal */
        tbody tr:last-child td {
            border-bottom: none;
        }
        /* Styling for no data message */
        .no-data {
            background-color: rgba(0, 0, 0, 0.6);
            border: 1px solid #00f0ff;
            border-radius: 10px;
            padding: 30px;
            margin: 20px 0;
            box-shadow: 0 0 15px rgba(0, 240, 255, 0.5);
        }
        .no-data p {
            margin-bottom: 0;
        }
        /* New button container style */
        .button-row {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        /* --- ANIMATIONS --- */
        @keyframes glow{from{text-shadow:0 0 5px #00f0ff,0 0 10px hsl(184,100%,50%);}
            to{text-shadow:0 0 15px #00f0ff,0 0 30px #00f0ff;}}
        @keyframes pulse{0%,100%{transform:scale(1);}50%{transform:scale(1.02);}}
        @keyframes bounce{0%,20%,50%,80%,100%{transform:translateY(0);}
            40%{transform:translateY(-20px);}60%{transform:translateY(-10px);}}
        @keyframes shake{0%,100%{transform:translateX(0);}
            10%,30%,50%,70%,90%{transform:translateX(-5px);}
            20%,40%,60%,80%{transform:translateX(5px);}}
        @keyframes progress{0%{width:0;}100%{width:100%;}}
    </style>
</head>
<body>

<?php
// Display the logout button at the top-right only if the user is logged in
if (isset($_SESSION['username'])) {
    echo '<div class="logout-container">
        <a href="login.html" class="btn about-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>';
}
?>

<video autoplay muted loop playsinline id="bg-video">
    <source src="patient.mp4" type="video/mp4"/>
</video>
<div class="content">
    <h1>DocNowLk</h1>
    <p>Your Health, Our Priority</p>

<?php
// Check if the session variables are set.
if (isset($_SESSION['username']) && isset($_SESSION['userfname'])) {
    // Session is active, display the dashboard content.
    $patientName = htmlspecialchars($_SESSION['userfname']);
    $patientUsername = $_SESSION['username']; // Use the username for the query

    // Display the success message and patient name
    echo '<div class="success-container">
        <i class="fas fa-user-circle success-icon"></i>
        <h2>Welcome, ' . $patientName . '!</h2>
        <p>Your portal for managing appointments is ready.</p>
    </div>';

    // --- NEW SECTION: Display the patient\'s own appointments ---
    echo '<div class="top-row">
        <div class="section-title">My Appointments</div>
    </div>';

    // SQL query to fetch patient's appointments.
    $appt_sql = "SELECT d.doctorname, a.Date, a.Time, d.specialization
                 FROM appointments a 
                 JOIN doctor d ON a.doctorusername = d.username
                 WHERE a.patientusername = ?
                 ORDER BY a.Date DESC, a.Time ASC";
    
    $appt_stmt = $con->prepare($appt_sql);
    
    if ($appt_stmt) {
        $appt_stmt->bind_param("s", $patientUsername);
        $appt_stmt->execute();
        $appt_result = $appt_stmt->get_result();
        $appointments = $appt_result->fetch_all(MYSQLI_ASSOC);
        $appt_stmt->close();
        
        if (count($appointments) > 0) {
            echo '<div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Doctor</th>
                                <th>Specialization</th>
                                <th>Date</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>';
            foreach ($appointments as $appt) {
                echo '<tr>
                        <td>' . htmlspecialchars($appt['doctorname']) . '</td>
                        <td>' . htmlspecialchars($appt['specialization']) . '</td>
                        <td>' . htmlspecialchars($appt['Date']) . '</td>
                        <td>' . htmlspecialchars($appt['Time']) . '</td>
                    </tr>';
            }
            echo '   </tbody>
                    </table>
                </div>';
        } else {
            echo '<div class="no-data">
                    <p><i class="fas fa-calendar-check"></i> You have no upcoming appointments.</p>
                </div>';
        }
    } else {
        echo '<div class="error-container"><i class="fas fa-database error-icon"></i>
            <h2>System Error</h2><p>Could not fetch your appointments. Please try again later.</p></div>';
    }

    // --- ORIGINAL SECTION: Display all available doctors ---
    echo '
        <div class="top-row">
            <div class="section-title">Available Doctors</div>
            <div class="button-row">
                <a href="chanel.php" class="btn"><i class="fas fa-calendar-plus"></i> Channel&nbsp;Now</a>
            </div>
        </div>';

    // Fetch and display all doctors from the doctors table
    $doc_sql = "SELECT doctorname, email,specialization, date, time FROM doctor ORDER BY doctorname ASC";
    $doc_stmt = $con->prepare($doc_sql);

    if ($doc_stmt) {
        $doc_stmt->execute();
        $doc_result = $doc_stmt->get_result();
        $doctors = $doc_result->fetch_all(MYSQLI_ASSOC);
        $doc_stmt->close();

        // Check if there are any doctors
        if (count($doctors) > 0) {
            // Display doctors in a table
            echo '<div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Doctor Name</th>
                                <th>Email</th>
                                <th>Specialization</th>
                                <th>Date</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>';
            // Loop through each doctor and display in a table row
            foreach ($doctors as $r) {
                echo '<tr>
                        <td>' . htmlspecialchars($r['doctorname']) . '</td>
                        <td>' . htmlspecialchars($r['email']) . '</td>
                        <td>' . htmlspecialchars($r['specialization']) . '</td>
                        <td>' . htmlspecialchars($r['date']) . '</td>
                        <td>' . htmlspecialchars($r['time']) . '</td>
                    </tr>';
            }
            echo '   </tbody>
                    </table>
                </div>';
        } else {
            // Display message if no doctors found
            echo '<div class="no-data">
                    <p><i class="fas fa-user-md"></i> No doctors found in the database.</p>
                </div>';
        }
    } else {
        // Handle error in preparing the doctor query
        echo '<div class="error-container"><i class="fas fa-database error-icon"></i>
            <h2>System Error</h2><p>Could not fetch doctors. Please try again later.</p></div>';
        }
} else {
    // If session variables are not set, it means the user is not logged in.
    echo '<div class="error-container"><i class="fas fa-ban error-icon"></i>
        <h2>Access Denied</h2><p>Please log in to view the doctors list.</p>
        <a href="login.html" class="btn"><i class="fas fa-sign-in-alt"></i> Go to Login</a></div>';
}

// Close the database connection
$con->close();
?>
</div>
</body>
</html>