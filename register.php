<?php
include 'db.php';

if(isset($_POST['register'])){
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $mobile   = trim($_POST['mobile']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' OR mobile='$mobile'");
    if(mysqli_num_rows($check) > 0){
        $error = "Username or mobile already exists";
    } else {
        mysqli_query($conn, "INSERT INTO users(username,password,mobile) VALUES('$username','$hashed_password','$mobile')");
        $success = "Registration successful! You can login now.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div id="register">
    <div class="form-box">
        <h2>Register</h2>
        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
        <form method="POST">
            <label>Username</label>
            <input type="text" name="username" required>
            <label>Mobile</label>
            <input type="text" name="mobile" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit" name="register">Register</button>
        </form>
        <p style="text-align:center; margin-top:10px;">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </div>
</div>
</body>
</html>
