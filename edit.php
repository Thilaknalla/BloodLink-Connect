<?php
session_start();
include 'db.php';
if(!isset($_SESSION['user_id'])){
    header("Location: login.php"); 
    exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$isAdmin = $_SESSION['role'] === 'admin' ? true : false;

// Fetch donor with permission check
if($isAdmin){
    $result = mysqli_query($conn,"SELECT * FROM donor WHERE id='$id'");
} else {
    $result = mysqli_query($conn,"SELECT * FROM donor WHERE id='$id' AND user_id='$user_id'");
}

if(mysqli_num_rows($result)==0){ 
    echo "Access denied"; 
    exit(); 
}

$donor = mysqli_fetch_assoc($result);

// Handle update
if(isset($_POST['update'])){
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $blood = $_POST['blood'];
    $place = $_POST['place'];
    $contact = $_POST['contact'];

    mysqli_query($conn,"UPDATE donor SET name='$name', age='$age', gender='$gender', blood='$blood', place='$place', contact='$contact' WHERE id='$id'");
    echo "<script>alert('Updated successfully'); window.location='view.php';</script>";
}

// Handle delete
if(isset($_POST['delete'])){
    mysqli_query($conn,"DELETE FROM donor WHERE id='$id'");
    echo "<script>alert('Record deleted successfully'); window.location='view.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Donor</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
    <div id="editdon">
    <div class="form-box">
        <div class="form-header" style="display:flex; justify-content:space-between; align-items:center;">
            <h2>Edit Donor Details</h2>
            <!-- Delete button on the right side of heading -->
            <form method="POST" style="margin:0;">
                <button type="submit" name="delete" 
                    onclick="return confirm('Are you sure you want to delete this record?');"
                    style="background-color:#ff6b81; color:white; border:none; padding:5px 12px; border-radius:5px; cursor:pointer; font-size:14px;">
                    Delete
                </button>
            </form>
        </div>

        <form method="POST">
            <label>Name</label>
            <input type="text" name="name" value="<?= htmlspecialchars($donor['name']) ?>" required>

            <label>Age</label>
            <input type="number" name="age" value="<?= $donor['age'] ?>" required>

            <label>Gender</label>
            <select name="gender" required>
                <option <?= $donor['gender']=='Male'?'selected':'' ?>>Male</option>
                <option <?= $donor['gender']=='Female'?'selected':'' ?>>Female</option>
                <option <?= $donor['gender']=='Other'?'selected':'' ?>>Other</option>
            </select>

            <label>Blood Group</label>
            <select name="blood" required>
                <option <?= $donor['blood']=='A+'?'selected':'' ?>>A+</option>
                <option <?= $donor['blood']=='A-'?'selected':'' ?>>A-</option>
                <option <?= $donor['blood']=='B+'?'selected':'' ?>>B+</option>
                <option <?= $donor['blood']=='B-'?'selected':'' ?>>B-</option>
                <option <?= $donor['blood']=='AB+'?'selected':'' ?>>AB+</option>
                <option <?= $donor['blood']=='AB-'?'selected':'' ?>>AB-</option>
                <option <?= $donor['blood']=='O+'?'selected':'' ?>>O+</option>
                <option <?= $donor['blood']=='O-'?'selected':'' ?>>O-</option>
            </select>

            <label>Place</label>
            <input type="text" name="place" value="<?= htmlspecialchars($donor['place']) ?>" required>

            <label>Contact</label>
            <input type="text" name="contact" value="<?= htmlspecialchars($donor['contact']) ?>" required><br><br>

            <button type="submit" name="update">Update</button>
        </form>
    </div>
</div>

</body>
</html>
