<?php
session_start();
include 'db.php';

// Check if user came from OTP verification
if(!isset($_SESSION['otp_verified']) || !isset($_SESSION['mobile'])){
    header("Location: forgot_password.php");
    exit();
}

$mobile = $_SESSION['mobile'];

if(isset($_POST['reset_password'])){
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm_password']);

    if($password === $confirm){
        // Hash password before saving
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Update in DB
        mysqli_query($conn,"UPDATE users SET password='$hash', otp=NULL, otp_expiry=NULL WHERE mobile='$mobile'");

        // Clear OTP session
        unset($_SESSION['otp_verified']);
        unset($_SESSION['mobile']);

        // Success message and redirect to login page
        echo "<script>alert('Password updated successfully! Please login with your new password.'); window.location='login.php';</script>";
        exit;
    } else {
        $error = "Passwords do not match.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Reset Password</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div id="reset">
<div class="form-box">
    <h2>Reset Password</h2>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <label>New Password</label>
        <input type="password" name="password" required>
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required>
        <button type="submit" name="reset_password">Reset Password</button>
    </form>
</div>
</div>
</body>
</html>
