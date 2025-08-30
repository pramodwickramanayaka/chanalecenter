<?php
// Start the session at the very beginning of the script.
session_start();

// --- IMPORTANT: Database Connection ---
// Ensure your database name is 'hospitale' and the user is 'root' with no password.
$con = new mysqli("localhost", "root", "", "hospitale"); 

// Check for a database connection error.
if ($con->connect_error) {
    echo '<!DOCTYPE html><html><head><meta charset="UTF-8"/><meta name="viewport" content="width=device-width, initial-scale=1.0"/><title>DocNowLk - Error</title><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/><style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html,body{height:100%;font-family:\'Segoe UI\',sans-serif;}
        #bg-video{position:fixed;inset:0;width:100%;height:100%;object-fit:cover;z-index:-1;opacity:.7;}
        .content{position:relative;height:100vh;display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center;background:rgba(0,0,0,.6);color:#fff;padding:20px;}
        h1{font-size:3em;margin-bottom:10px;text-shadow:0 0 10px #00f0ff,0 0 20px #00f0ff,0 0 30px #00f0ff;animation:glow 2s infinite alternate;}
        .error-container{background:rgba(255,100,100,.2);border:2px solid #ff6464;border-radius:20px;padding:40px;margin:20px;max-width:600px;box-shadow:0 0 30px rgba(255,100,100,.3);animation:shake .5s;}
        .error-icon{font-size:4em;color:#ff6464;margin-bottom:20px;text-shadow:0 0 20px #ff6464;}
        .btn{display:inline-flex;align-items:center;gap:8px;padding:12px 28px;font-size:16px;border:none;border-radius:50px;background:#00f0ff;color:#000;text-decoration:none;cursor:pointer;transition:.3s;box-shadow:0 0 10px #00f0ff,0 0 20px #00f0ff;}
        .btn:hover{background:#fff;color:#007BFF;box-shadow:0 0 20px #00f0ff,0 0 40px #00f0ff;}
        @keyframes glow{from{text-shadow:0 0 5px #00f0ff,0 0 10px hsl(184,100%,50%);}to{text-shadow:0 0 15px #00f0ff,0 0 30px #00f0ff;}}
        @keyframes shake{0%,100%{transform:translateX(0);}10%,30%,50%,70%,90%{transform:translateX(-5px);}20%,40%,60%,80%{transform:translateX(5px);}}
    </style></head><body><video autoplay muted loop playsinline id="bg-video"><source src="https://videos.pexels.com/video-files/7195621/7195621-uhd_2732_1440_25fps.mp4" type="video/mp4"/></video><div class="content"><h1>DocNowLk</h1><p>Your Health, Our Priority</p><div class="error-container"><i class="fas fa-exclamation-triangle error-icon"></i><h2>Connection Error</h2><p>Database connection failed: ' . $con->connect_error . '</p><a href="loginpatient.html" class="btn"><i class="fas fa-redo"></i> Back to Login</a></div></div></body></html>';
    exit;
}

// Check if the form was submitted.
if (isset($_POST['username'])) {
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['userfname'] = $_POST['userfname'];
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
        html,body{height:100%;font-family:'Segoe UI',sans-serif;}
        /* --- VIDEO BACKGROUND --- */
        #bg-video{position:fixed;inset:0;width:100%;height:100%;object-fit:cover;z-index:-1;opacity:.7;}
        /* --- LOGOUT BUTTON CONTAINER (NEW) --- */
        .logout-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
        /* --- CONTENT CONTAINER --- */
        .content{
            position:relative;min-height:100vh;display:flex;flex-direction:column;
            justify-content:center;align-items:center;text-align:center;
            background:rgba(0,0,0,.6);color:#fff;padding:20px;
            overflow-y: auto;
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
        .success-container, .error-container, .info-container {
            border-radius:20px;padding:40px;margin:20px auto;max-width:600px;
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
        .doctor-details-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
        }
        .detail-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            text-align: left;
        }
        .detail-row i {
            color: #00f0ff;
            width: 20px;
            text-align: center;
        }
        .detail-row span {
            color: #fff;
            font-size: 1.1em;
        }
        /* --- APPOINTMENT TABLE STYLES --- */
        .table-container {
            width: 90%;
            max-width: 600px;
            margin-top: 20px;
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
            margin: 20px auto;
            max-width: 600px;
            box-shadow: 0 0 15px rgba(0, 240, 255, 0.5);
        }
        .no-data p {
            margin-bottom: 0;
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
    <source src="doc.mp4" type="video/mp4"/>
</video>

<div class="content">
    <h1>DocNowLk</h1>
    <p>Your Health, Our Priority</p>

<?php
// Check if the session variables are set.
if (isset($_SESSION['username']) && isset($_SESSION['userfname'])) {
    // Use the username, which is the unique identifier for the doctor.
    $doctorUsername = $_SESSION['username'];
    $appointments = [];

    // Step 1: Fetch appointments AND patient details for the logged-in doctor.
    // This is the correct query to get all necessary data in one go.
    $appSql = "SELECT a.date, a.time, p.email, p.telephone, CONCAT(p.fname, ' ', p.lname) AS patientname 
                FROM appointments a 
                INNER JOIN patient p ON a.patientusername = p.username 
                WHERE a.doctorusername = ? 
                ORDER BY a.date ASC";

    $appStmt = $con->prepare($appSql);
    if ($appStmt) {
        $appStmt->bind_param('s', $doctorUsername);
        $appStmt->execute();
        $appResult = $appStmt->get_result();
        while ($row = $appResult->fetch_assoc()) {
            $appointments[] = $row;
        }
        $appStmt->close();
    }

    $appointmentCount = count($appointments);

    // Step 2: Fetch the doctor's personal data.
    $doctorDataSql = "SELECT doctorname, email, specialization FROM doctor WHERE username = ?";
    $doctorDataStmt = $con->prepare($doctorDataSql);
    if ($doctorDataStmt) {
        $doctorDataStmt->bind_param('s', $_SESSION['username']);
        $doctorDataStmt->execute();
        $doctorDataResult = $doctorDataStmt->get_result();
        $doctorDetails = $doctorDataResult->fetch_assoc();
        $doctorDataStmt->close();
    }

    // Check if doctor details were found, otherwise set default values to prevent errors
    if (empty($doctorDetails)) {
        $doctorDetails = [
            'doctorname' => 'N/A',
            'email' => 'N/A',
            'specialization' => 'N/A'
        ];
    }

    // Step 3: Display doctor's personal information and appointment count.
    echo '<div class="success-container">
        <i class="fas fa-user-md success-icon"></i>
        <h2>Welcome, Dr. ' . htmlspecialchars($doctorDetails['doctorname']) . '!</h2>
        <div class="doctor-details-grid">
            <div class="detail-row">
                <i class="fas fa-envelope"></i>
                <span>' . htmlspecialchars($doctorDetails['email']) . '</span>
            </div>
            <div class="detail-row">
                <i class="fas fa-stethoscope"></i>
                <span>' . htmlspecialchars($doctorDetails['specialization']) . '</span>
            </div>
            <div class="detail-row">
                <i class="fas fa-calendar-alt"></i>
                <span>You have ' . $appointmentCount . ' appointments scheduled.</span>
            </div>
        </div>
    </div>';

    // Step 4: Display the appointments and patient details tables.
    if (!empty($appointments)) {
        echo '<div class="table-container">
                <h2><i class="fas fa-calendar-check"></i> My Patients\' Appointments</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Patient Name</th>
                            <th>Date</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach ($appointments as $appt) {
            echo '<tr>
                    <td>' . htmlspecialchars($appt['patientname']) . '</td>
                    <td>' . htmlspecialchars($appt['date']) . '</td>
                    <td>' . htmlspecialchars($appt['time']) . '</td>
                </tr>';
        }
        echo '</tbody>
                </table>
            </div>';

        // Display the patient details table
        echo '<div class="table-container">
                <h2><i class="fas fa-users"></i> Patient Details</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Telephone</th>
                        </tr>
                    </thead>
                    <tbody>';
        // The appointments array now already contains all the patient details
        foreach ($appointments as $appt) {
            echo '<tr>
                    <td>' . htmlspecialchars($appt['patientname']) . '</td>
                    <td>' . htmlspecialchars($appt['email']) . '</td>
                    <td>' . htmlspecialchars($appt['telephone']) . '</td>
                </tr>';
        }
        echo '</tbody>
                </table>
            </div>';

    } else {
        echo '<div class="no-data"><p>You have no appointments booked at this time.</p></div>';
    }
    
} else {
    // Session variables are not set, display a form to get user data.
    echo '<div class="info-container">
        <h2>Doctor Login</h2>
        <p>Your session details were not found. Please enter your username and full name to view your dashboard.</p>
        <form action="" method="POST">
            <div class="detail-row">
                <i class="fas fa-user-circle"></i>
                <input type="text" name="username" placeholder="Database Username" required>
            </div>
            <div class="detail-row">
                <i class="fas fa-user-tag"></i>
                <input type="text" name="userfname" placeholder="Full Doctor Name" required>
            </div>
            <button type="submit" class="btn"><i class="fas fa-sign-in-alt"></i> View Dashboard</button>
        </form>
    </div>';
}

// Close the database connection.
$con->close();
?>
</div>
</body>
</html>