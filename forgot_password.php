<?php
session_start();
include 'db.php';

// Include Composer autoload (after you install Twilio with composer require twilio/sdk)
require __DIR__ . '/vendor/autoload.php';

use Twilio\Rest\Client;

if(isset($_POST['send_otp'])){
    $mobile = trim($_POST['mobile']);
    $user = mysqli_query($conn,"SELECT * FROM users WHERE mobile='$mobile'");

    if(mysqli_num_rows($user) > 0){
        $otp = rand(100000,999999);
        $expiry = date("Y-m-d H:i:s", strtotime("+10 minutes"));
        mysqli_query($conn,"UPDATE users SET otp='$otp', otp_expiry='$expiry' WHERE mobile='$mobile'");
        $_SESSION['mobile'] = $mobile;

      // Twilio credentials
   $sid           = "ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";  // Your Twilio Account SID
    $token         = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";    // Your Twilio Auth Token
    $twilio_number = "+1xxxxxxxxxx";   ; // Your Twilio number

        // Initialize Twilio Client
        $client = new Client($sid, $token);

        try {
            $client->messages->create(
                "+91" . $mobile, // Sending to Indian mobile (add +91 or country code)
                [
                    'from' => $twilio_number,
                    'body' => "Your BloodLink Connect OTP is: $otp. It will expire in 10 minutes."
                ]
            );

            echo "<script>alert('OTP sent to your mobile via SMS'); window.location='verify_otp.php';</script>";
        } catch (Exception $e) {
            $error = "Failed to send OTP: " . $e->getMessage();
        }

    } else {
        $error = "Mobile number not registered";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Forgot Password</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div id="forgot">
<div class="form-box">
    <h2>Forgot Password</h2>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST">
        <label>Enter Registered Mobile Number</label>
        <input type="text" name="mobile" required>
        <button type="submit" name="send_otp">Send OTP</button>
    </form>
</div>
</div>
</body>
</html>
