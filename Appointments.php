<?php
// Database connection
$db = new mysqli('localhost', 'root', '', 'hospitale');

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Fetch all appointments with patient and doctor names
// Using CONCAT to combine fname and lname from both patient and doctor tables
$sql = "SELECT 
            CONCAT(p.fname, ' ', p.lname) AS patientname, 
            CONCAT(d.doctorname) AS doctorname, 
            a.date, 
            a.time
        FROM appointments a
        INNER JOIN patient p ON a.patientusername = p.username
        INNER JOIN doctor d ON a.doctorusername = d.username
        ORDER BY a.date DESC";
        
$result = $db->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Appointments - DocNowLk</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }

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
      justify-content: flex-start;
      align-items: center;
      text-align: center;
      background-color: rgba(0, 0, 0, 0.5);
      color: white;
      padding: 20px;
      overflow-y: auto;
    }

    h1 {
      font-size: 3em;
      margin: 30px 0;
      text-shadow: 0 0 10px #00f0ff, 0 0 20px #00f0ff, 0 0 30px #00f0ff;
      animation: glow 2s infinite alternate;
    }

    .table-container {
      width: 90%;
      max-width: 1200px;
      margin: 20px 0;
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 30px;
      background-color: rgba(0, 0, 0, 0.3);
    }

    th, td {
      padding: 15px;
      text-align: left;
      border-bottom: 1px solid rgba(0, 240, 255, 0.3);
    }

    th {
      background-color: rgba(0, 240, 255, 0.2);
      color: #00f0ff;
      text-shadow: 0 0 5px #00f0ff;
      position: sticky;
      top: 0;
    }

    tr:hover {
      background-color: rgba(0, 240, 255, 0.1);
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
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

    .btn i {
      font-size: 0.95em;
      line-height: 0;
    }

    .btn:hover {
      background-color: #ffffff;
      color: #007BFF;
      box-shadow: 0 0 20px #00f0ff, 0 0 40px #00f0ff;
    }

    .no-data {
      background-color: rgba(0, 0, 0, 0.6);
      padding: 30px;
      border: 1px solid #00f0ff;
      border-radius: 10px;
      margin: 20px 0;
      box-shadow: 0 0 15px rgba(0, 240, 255, 0.5);
    }

    .stats-container {
      display: flex;
      justify-content: space-around;
      width: 90%;
      max-width: 1200px;
      margin: 20px 0;
      flex-wrap: wrap;
    }

    .stat-card {
      background-color: rgba(0, 0, 0, 0.6);
      border: 1px solid rgba(0, 240, 255, 0.3);
      border-radius: 10px;
      padding: 20px;
      margin: 10px;
      min-width: 200px;
      flex: 1;
      box-shadow: 0 0 15px rgba(0, 240, 255, 0.3);
    }

    .stat-value {
      font-size: 2.5em;
      font-weight: bold;
      color: #00f0ff;
      text-shadow: 0 0 5px #00f0ff;
    }

    .stat-label {
      font-size: 1.2em;
      margin-top: 10px;
      color: #d9faff;
    }

    @keyframes glow {
      from {
        text-shadow: 0 0 5px #00f0ff, 0 0 10px hsl(184, 100%, 50%);
      }
      to {
        text-shadow: 0 0 15px #00f0ff, 0 0 30px #00f0ff;
      }
    }
  </style>
</head>
<body>
  <video autoplay muted loop playsinline id="bg-video">
    <source src="appointments.mp4" type="video/mp4" />
  </video>

  <div class="content">
    <h1><i class="fas fa-calendar-check"></i> Appointments Dashboard</h1>
    
    <?php
    // Calculate statistics
    $totalAppointments = $result->num_rows;
    ?>
    
    <div class="stats-container">
      <div class="stat-card">
        <div class="stat-value"><?php echo $totalAppointments; ?></div>
        <div class="stat-label">Total Appointments</div>
      </div>
    </div>
    
    <?php if ($result->num_rows > 0): ?>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Patient Name</th>
              <th>Doctor Name</th>
              <th>Appointment Date</th>
              <th>Time</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['patientname']) ?></td>
              <td><?= htmlspecialchars($row['doctorname']) ?></td>
              <td><?= date('F j, Y', strtotime($row['date'])) ?></td>
              <td><?= htmlspecialchars($row['time']) ?></td>
            </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="no-data">
        <p><i class="fas fa-calendar-times"></i> No appointments found</p>
      </div>
    <?php endif; ?>

    <div>
      <a href="admindashboard.html" class="btn">
        <i class="fas fa-arrow-left"></i> Back to Home
      </a>
    </div>
  </div>

  <?php $db->close(); ?>
</body>
</html>