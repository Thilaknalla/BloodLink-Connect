<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    $_SESSION['redirect_to'] = $_SERVER['PHP_SELF'];
    header("Location: login.php");
    exit();
}

if(isset($_POST['upload'])){
    $user_id = $_SESSION['user_id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $blood = $_POST['blood'];
    $place = $_POST['place'];
    $contact = $_POST['contact'];

    $stmt = $conn->prepare("INSERT INTO donor(user_id,name,age,gender,blood,place,contact,uploaded_by) VALUES(?,?,?,?,?,?,?,?)");
    $stmt->bind_param("isssssss",$user_id,$name,$age,$gender,$blood,$place,$contact,$_SESSION['username']);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Your details are added'); window.location='view.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Upload Donor</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div id="upload">
    <div class="form-box">
        <h2>Upload Donor Details</h2>
        <form method="POST">
            <label>Name</label>
            <input type="text" name="name" required>
            <label>Age</label>
            <input type="number" name="age" required>
            <label>Gender</label>
            <select name="gender" required>
                <option value="">Select Gender</option>
                <option>Male</option><option>Female</option><option>Other</option>
            </select>
            <label>Blood Group</label>
            <select name="blood" required>
                <option value="">Select Blood Group</option>
                <option>A+</option><option>A-</option>
                <option>B+</option><option>B-</option>
                <option>AB+</option><option>AB-</option>
                <option>O+</option><option>O-</option>
            </select>
            <label>Place</label>
            <input type="text" name="place" required>
            <label>Contact</label>
           <input type="text" name="contact" required pattern="\d{10}" maxlength="10">
            <button type="submit" name="upload">Upload</button>
        </form>
        <a href="view.php" style="display:block; text-align:center; margin-top:15px;">Back to Dashboard</a>
    </div>
</div>
</body>
</html>
