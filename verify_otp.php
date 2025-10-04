<?php
session_start();
include 'db.php';
require __DIR__ . '/vendor/autoload.php';  // Twilio SDK
use Twilio\Rest\Client;

if(!isset($_SESSION['mobile'])){
    die("Session expired. Please go back and try again.");
}

$mobile = $_SESSION['mobile'];

// Verify OTP
if(isset($_POST['verify_otp'])){
    $entered_otp = trim($_POST['otp']);
    $query = mysqli_query($conn, "SELECT otp, otp_expiry FROM users WHERE mobile='$mobile'");
    $row = mysqli_fetch_assoc($query);

    if($row){
        $db_otp = $row['otp'];
        $db_expiry = $row['otp_expiry'];

        if($entered_otp == $db_otp && strtotime($db_expiry) >= time()){
            $_SESSION['otp_verified'] = true;
            header("Location: reset_password.php");
            exit;
        } else {
            $error = "Invalid or expired OTP. Please try again.";
        }
    }
}

// Resend OTP
if(isset($_POST['resend_otp'])){
    $otp = rand(100000,999999);
    $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));
    mysqli_query($conn,"UPDATE users SET otp='$otp', otp_expiry='$expiry' WHERE mobile='$mobile'");

    // Twilio credentials
   $sid           = "ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";  // Your Twilio Account SID
    $token         = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";    // Your Twilio Auth Token
    $twilio_number = "+1xxxxxxxxxx";   ; // Your Twilio number

    $client = new Client($sid, $token);

    try {
        $client->messages->create(
            "+91" . $mobile,
            [
                'from' => $twilio_number,
                'body' => "🔑 Your new BloodLink Connect OTP is: $otp. It will expire in 10 minutes."
            ]
        );
        $success = "A new OTP has been sent to your mobile.";
    } catch (Exception $e) {
        $error = "Failed to resend OTP: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <link rel="stylesheet" href="style.css">
    <script>
        let countdown = 30;
        let timer;

        function startCountdown() {
            const btn = document.getElementById("resendBtn");
            const timerSpan = document.getElementById("timer");

            btn.disabled = true;
            timer = setInterval(() => {
                countdown--;
                timerSpan.innerText = countdown;

                if (countdown <= 0) {
                    clearInterval(timer);
                    btn.disabled = false;
                    timerSpan.innerText = "";
                }
            }, 1000);
        }

        window.onload = startCountdown;
    </script>
</head>
<body>
<div id="forgot">
  <div class="form-box">
    <h2>Verify OTP</h2>
    <?php 
      if(isset($error)) echo "<p style='color:red;'>$error</p>"; 
      if(isset($success)) echo "<p style='color:green;'>$success</p>"; 
    ?>

    <form method="POST" style="display: flex; justify-content: space-between; align-items: flex-start;">
        <div>
            <label>Enter OTP</label>
            <input type="text" name="otp" required>
            <button type="submit" name="verify_otp" style="margin-top: 10px; padding: 5px 12px; font-size: 14px;">Verify</button>
        </div>

        <div style="text-align: center;">
            <button type="submit" name="resend_otp" id="resendBtn" style="margin-top: 23px; padding: 5px 12px; font-size: 14px;">Resend OTP</button>
            <div id="timer" style="margin-top: 5px; font-size: 14px;">00:30</div>
        </div>
    </form>
  </div>
</div>


</body>
</html> 