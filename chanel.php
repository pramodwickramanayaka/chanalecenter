<?php
/* ---------------------------------------------------------------
chanel.php – book an appointment and show details
------------------------------------------------------------- */
session_start();

/***** 1. CONFIG ************************************************/
$db = new mysqli('localhost', 'root','', 'hospitale');
if ($db->connect_error) {
die('DB connection failed: ' . $db->connect_error);
}

/* Which session key stores the logged patient? */
$SESSION_KEY = 'patient_username';

/***** 2. FETCH DOCTOR LIST *************************************/
$doctorList = [];
$res = $db->query("SELECT username, doctorname FROM doctor ORDER BY doctorname"); // FIX: Select both username and doctorname
while ($row = $res->fetch_assoc()) {
    // FIX: Store both username and doctorname
    $doctorList[] = [
        'username' => $row['username'],
        'doctorname' => $row['doctorname']
    ];
}
$res->free();

/***** 3. DEFAULT PAGE STATE ************************************/
$state = 'form'; // form | success | error
$message = '';
$patientUser = $_SESSION[$SESSION_KEY] ?? ''; // Get patient username from session

/***** 4. HANDLE FORM SUBMIT ************************************/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$patientUser = $_SESSION[$SESSION_KEY] ?? trim($_POST['patientusername'] ?? '');

$doctorUsername = trim($_POST['doctor'] ?? ''); // FIX: Get the doctor's username from the form
$date = $_POST['date'] ?? '';
$time = $_POST['time'] ?? '';

/* ——— Validation ——— */
if ($doctorUsername === '' || $patientUser === '' || $date === '' || $time === '') {
    $state = 'error';
    $message = 'All fields are required.';
} else {
    // Corrected INSERT statement to use a consistent column name, e.g., 'app_time'
    $sql = "INSERT INTO appointments (doctorusername, patientusername,date,time)
     VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('ssss', $doctorUsername, $patientUser, $date, $time); // FIX: Use the doctor's username
        if ($stmt->execute()) {
            $state = 'success';
            $message = 'Appointment booked successfully!';
        } else {
            $state = 'error';
            $message = 'Insert failed: ' . $db->error;
        }
        $stmt->close();
    } else {
        $state = 'error';
        $message = 'Preparation failed: ' . $db->error;
    }
}
}


$db->close();
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title>Channel a Doctor</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
/* Same CSS as before, plus a new style for the appointments section */
* { margin:0;padding:0;box-sizing:border-box; }
html,body{height:100%;font-family:'Segoe UI',sans-serif;}
#bg-video{position:fixed;top:0;left:0;width:100%;height:100%;object-fit:cover;z-index:-1;}
.content{position:relative;height:100vh;display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center;background:rgba(0,0,0,.5);color:#fff;padding:20px;}
h1{font-size:3em;margin-bottom:10px;text-shadow:0 0 10px #00f0ff,0 0 20px #00f0ff,0 0 30px #00f0ff;animation:glow 2s infinite alternate;}
p{font-size:1.2em;margin-bottom:30px;color:#d9faff;}
.btn{display:inline-flex;align-items:center;gap:8px;margin:10px;padding:12px 28px;font-size:16px;border:none;border-radius:50px;background:#00f0ff;color:#000;text-decoration:none;transition:.3s;box-shadow:0 0 10px #00f0ff,0 0 20px #00f0ff;cursor:pointer;}
.btn:hover{background:#fff;color:#007BFF;box-shadow:0 0 20px #00f0ff,0 0 40px #00f0ff;}
.form-box{background:rgba(0,0,0,.6);padding:30px 40px;border:1px solid #00f0ff;border-radius:12px;box-shadow:0 0 20px #00f0ff;width:320px;}
.form-box select,.form-box input{width:100%;margin-bottom:18px;padding:10px 12px;border:none;border-radius:8px;}
.status-box{background:rgba(0,240,255,.2);border:2px solid #00f0ff;border-radius:20px;padding:40px;box-shadow:0 0 30px rgba(0,240,255,.3);}
.status-box.error{background:rgba(255,100,100,.2);border-color:#ff6464;}
.appointments-box{background:rgba(0,0,0,.6);padding:30px 20px;border:1px solid #00f0ff;border-radius:12px;box-shadow:0 0 20px #00f0ff;margin-top:20px;width:90%;max-width:600px;text-align:left;}
.appointments-box h2{font-size:2em;text-align:center;text-shadow:0 0 5px #00f0ff;}
.appointments-box table{width:100%;border-collapse:collapse;margin-top:20px;color:#d9faff;}
.appointments-box th, .appointments-box td{padding:10px;border:1px solid #00f0ff;text-align:left;}
.appointments-box th{background:rgba(0,240,255,.2);color:#fff;}
@keyframes glow{from{text-shadow:0 0 5px #00f0ff,0 0 10px hsl(184,100%,50%);}to{text-shadow:0 0 15px #00f0ff,0 0 30px #00f0ff;}}
</style>
</head>
<body>
<video autoplay muted loop playsinline id="bg-video">
<source src="chanel.mp4" type="video/mp4">
</video>

<div class="content">
<h1>Channel a Doctor</h1>
<p>Select a doctor and date for your appointment</p>

<?php if ($state === 'form'): ?>
    <a href="loginpatient.php" class="btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
   <form class="form-box" method="POST" action="">
   <select name="doctor" required>
       <option value="">— Select Doctor —</option>
       <?php foreach ($doctorList as $doc): ?>
       <option value="<?= htmlspecialchars($doc['username']) ?>">
      <?= htmlspecialchars($doc['doctorname']) ?>
    </option>
       <?php endforeach; ?>
   </select>

   <?php if (isset($_SESSION[$SESSION_KEY])): ?>
   <input type="text" name="patientusername"
       value="<?= htmlspecialchars($_SESSION[$SESSION_KEY]) ?>" readonly>
   <?php else: ?>
   <input type="text" name="patientusername" placeholder="Your Username" required>
   <?php endif; ?>

   <input type="date" name="date" required min="<?= date('Y-m-d'); ?>">
   <input type="text" name="time" placeholder="e.g., 09:30 AM" required>

   <button class="btn" type="submit"><i class="fas fa-calendar-check"></i> Book</button>
   </form>
<?php else: ?>
   <div class="status-box <?= $state === 'error' ? 'error' : '' ?>">
   <h2><?= $state === 'success' ? 'Success!' : 'Oops…' ?></h2>
   <p><?= htmlspecialchars($message) ?></p>
    <a href="loginpatient.php" class="btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
   </div>
<?php endif; ?>

</div>
</body>
</html>