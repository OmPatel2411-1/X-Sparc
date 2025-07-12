<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "medicalportal");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password.";
    } else {
        $query = "SELECT * FROM user WHERE email = ? AND status = 'approved'";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["role"] = $user["role"];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Medical Portal</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f0f4f8; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-container { background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px; }
        h2 { color: #007bff; text-align: center; }
        .error { color: red; text-align: center; margin-bottom: 15px; }
        label { display: block; margin: 10px 0 5px; }
        input { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 5px; }
        button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login to Medical Portal</h2>
        <?php if (isset($error)) { ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php } ?>
        <form method="POST" action="">
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
            <p style="text-align: center; margin-top: 10px;">Don't have an account? <a href="signup.php">Signup</a></p>
        </form>
    </div>
</body>
</html>