<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
<?php
// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["pass"];

    // Simple validation (in real app, check against database)
    if ($username === "admin" && $password === "1234") {
        // Successful login - redirect to dashboard
        header("Location:admindashboard.html");
        exit();
    } else {
        // Failed login - show error
        echo "<h1>Login Failed</h1>";
        echo "<p>Invalid username or password.</p>";
        echo '<p><a href="index.html">Try Again</a></p>';
    }
} else {
    // If someone tries to access this page directly
    header("Location:login.html");
    exit();
}
?>
</body>
</html>
