<?php
session_start();
include 'db.php';

$user_id = $_SESSION['user_id'] ?? 0;
$role = $_SESSION['role'] ?? 'user';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : "";

// Base query
$sql = "SELECT d.*, u.username FROM donor d LEFT JOIN users u ON d.user_id = u.id";

// Add search filter
if(!empty($search)){
    $sql .= " WHERE d.name LIKE '%$search%' OR d.blood LIKE '%$search%' OR d.place LIKE '%$search%'";
}

$sql .= " ORDER BY d.id DESC";

$donors = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Donors - BloodLink Connect</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<style>
.search-bar {
  display: flex;
  justify-content: center;
  gap: 5px;
  margin: 10px auto;
}
.search-bar input {
  width: 22cm; /* long search bar */
  padding: 6px;
  border: 1px solid #ccc;
  border-radius: 5px;
}
.search-bar button {
  padding: 6px 12px;
  border: none;
  border-radius: 5px;
  background: #ff6b81; /* reddish-pink */
  color: #fff;
  cursor: pointer;
}
  .search-bar button:hover {
  background-color: #ff4c70; /* slightly darker on hover */
}
}
</style>

</head>
<body>
<div class="navbar">
    <span class="material-icons hamburger" onclick="toggleSidebar()">menu</span>
    <h2>BloodLink Connect</h2>
</div>

<div id="sidebar">
    
    <ul>
        <li><button class="sidebar-btn" onclick="location.href='about.php'">About</button></li>
        <li><button class="sidebar-btn" onclick="location.href='upload.php'">Upload</button></li>
        <li><button class="sidebar-btn" onclick="location.href='view.php'">View Details</button></li>
        <li><button class="sidebar-btn" onclick="location.href='bloodbanks.php'">Blood Banks</button></li>
        <li><button class="sidebar-btn" onclick="location.href='precautions.php'">Precautions</button></li>
        <li><button class="sidebar-btn" onclick="location.href='logout.php'">Logout</button></li>
    </ul>
</div>
<div id="overlay"></div>

<div class="page-header">
        <br><br><br><h2 style="text-align:center;">All Donor Details</h2>
        <form method="GET" class="search-bar">
            <input type="text" name="search" placeholder="Search by name, blood, or place" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
</div>
<div id="donorList">
<?php if($donors->num_rows > 0): ?>
    <?php while($row = $donors->fetch_assoc()): ?>
        <div class="donor-box">
            <strong>Name:</strong> <?= htmlspecialchars($row['name']) ?> |
            <strong>Age:</strong> <?= htmlspecialchars($row['age']) ?> |
            <strong>Gender:</strong> <?= htmlspecialchars($row['gender']) ?> |
            <strong>Blood Group:</strong> <?= htmlspecialchars($row['blood']) ?> |
            <strong>Place:</strong> <?= htmlspecialchars($row['place']) ?> |
            <strong>Contact:</strong> <?= htmlspecialchars($row['contact']) ?> |
            <strong>Uploaded by:</strong> <?= htmlspecialchars($row['username']) ?>

            <?php 
            // Show edit button only if the logged-in user is owner or admin
            if($user_id && ($row['user_id'] == $user_id || $role == 'admin')): ?>
                <a href="edit.php?id=<?= $row['id'] ?>" class="edit-btn">Edit</a>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p style="text-align:center;">No donor details found.</p>
<?php endif; ?>
</div>

<div class="footer">&copy; 2025 BloodLink-Connect Designed by THILAK NALLA</div>
<script src="script.js"></script>
</body>
</html>
