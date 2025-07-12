<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "medicalportal");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into user table
    $query = "INSERT INTO user (name, email, password, role, status) VALUES (?, ?, ?, ?, 'pending')";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $hashed_password, $role);
    
    try {
        if (mysqli_stmt_execute($stmt)) {
            // Insert into role-specific table
            if ($role == 'patient') {
                $query = "INSERT INTO patients (user_id, medical_history, blood_group, allergies, emergency_contact) VALUES (LAST_INSERT_ID(), '', '', '', '')";
            } else {
                $query = "INSERT INTO doctor (user_id, specialization, medical_license_number, hospital_clinic_name, years_of_experience) VALUES (LAST_INSERT_ID(), '', '', '', 0)";
            }
            mysqli_query($conn, $query);
            $signup_success = "Signup successful! Please wait for admin approval.";
        }
    } catch (mysqli_sql_exception $e) {
        $signup_error = "Error: " . $e->getMessage();
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Medical Portal</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f0f4f8; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .signup-container { background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px; }
        h2 { color: #007bff; text-align: center; }
        .error { color: red; text-align: center; margin-bottom: 15px; }
        .success { color: green; text-align: center; margin-bottom: 15px; }
        label { display: block; margin: 10px 0 5px; }
        input, select { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 5px; }
        button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Signup for Medical Portal</h2>
        <?php if (isset($signup_error)) { ?>
            <p class="error"><?php echo htmlspecialchars($signup_error); ?></p>
        <?php } ?>
        <?php if (isset($signup_success)) { ?>
            <p class="success"><?php echo htmlspecialchars($signup_success); ?></p>
        <?php } ?>
        <form method="POST" action="">
            <label>Name</label>
            <input type="text" name="name" required>
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit">Signup</button>
            <p style="text-align: center; margin-top: 10px;">Already have an account? <a href="login.php">Login</a></p>
        </form>
    </div>
</body>
</html>