<?php
session_start();
include 'db.php';

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : "";

// Base query
$sql = "SELECT d.*, u.username FROM donor d LEFT JOIN users u ON d.user_id = u.id";

// Add search filter if search is entered
if(!empty($search)){
    $sql .= " WHERE d.name LIKE '%$search%' OR d.blood LIKE '%$search%' OR d.place LIKE '%$search%'";
}

$sql .= " ORDER BY d.id DESC";

$donors = $conn->query($sql);

$totalDonors = $donors->num_rows;
$recentDonor = $donors->fetch_assoc();
// Total donors
$totalDonorsQuery = $conn->query("SELECT COUNT(*) AS total FROM donor");
$totalDonors = ($totalDonorsQuery->fetch_assoc())['total'] ?? 0;

// Recent donor
$recentDonorQuery = $conn->query("SELECT name FROM donor ORDER BY id DESC LIMIT 1");
$recentDonor = ($recentDonorQuery->fetch_assoc())['name'] ?? 'N/A';

// Blood group summary
$bloodSummaryQuery = $conn->query("SELECT blood, COUNT(*) AS count FROM donor GROUP BY blood");
$bloodSummaryHTML = '';
$bloodGroups = [];
while($row = $bloodSummaryQuery->fetch_assoc()){
    $bloodGroups[$row['blood']] = $row['count'];
    $bloodSummaryHTML .= "{$row['blood']}: {$row['count']}<br>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard - BloodLink Connect</title>
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

</style>

</head>
<body>
<div class="navbar">
    <span class="material-icons hamburger" onclick="toggleSidebar()">menu</span>
    <h2>BloodLink Connect</h2>
</div>

<div id="sidebar">
 
    <ul>
        <li><button class="sidebar-btn" onclick="location.href='index.php'">Home</button></li>
        <li><button class="sidebar-btn" onclick="location.href='about.php'">About</button></li>
        <li><button class="sidebar-btn" onclick="location.href='upload.php'">Upload</button></li>
        <li><button class="sidebar-btn" onclick="location.href='bloodbanks.php'">Blood Banks</button></li>
        <li><button class="sidebar-btn" onclick="location.href='precautions.php'">Precautions</button></li>
        <li><button class="sidebar-btn" onclick="location.href='login.php'">Login</button></li>
    </ul>

</div>



<!-- Dashboard Page -->
<div id="view" class="page" style="display:block;">
    <h2 style="margin-top:0;">Dashboard</h2>
    <div class="dashboard-cards" id="dashboardCards">  
        <!-- Total Donors -->
        <div class="dashboard-card" id="totalDonorsCard">
            <span class="material-icons">people</span>
            <div>
                <div class="count" id="totalDonorsCount"><?php echo $totalDonors; ?></div>
                <div class="label">Total Donors</div>
            </div>
        </div>

        <!-- Recent Donor -->
        <div class="dashboard-card" id="recentDonorCard">
            <span class="material-icons">person</span>
            <div>
                <div class="count" id="recentDonorName"><?php echo $recentDonor; ?></div>
                <div class="label">Recent Donor</div>
            </div>
        </div>

        <!-- Blood Group Summary -->
        <div class="dashboard-card" id="bloodSummaryCard">
            <span class="material-icons">bloodtype</span>
            <div>
                <div class="count" id="bloodGroupCount"><?php echo count($bloodGroups); ?></div>
                <div class="label">Blood Group Summary</div>
                <div id="bloodSummaryList"><?php echo $bloodSummaryHTML; ?></div>
            </div>
        </div>
    </div>
    <div class="page-header">
        <h2 style="text-align:center;">All Donor Details</h2>
        <form method="GET" class="search-bar">
            <input type="text" name="search" placeholder="Search by name, blood, or place" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
</div>
<div id="donorList">
<?php if($donors->num_rows>0): ?>
    <?php foreach($donors as $row): ?>
        <div class="donor-box">
            <strong>Name:</strong> <?= htmlspecialchars($row['name']) ?> |
            <strong>Age:</strong> <?= htmlspecialchars($row['age']) ?> |
            <strong>Gender:</strong> <?= htmlspecialchars($row['gender']) ?> |
            <strong>Blood Group:</strong> <?= htmlspecialchars($row['blood']) ?> |
            <strong>Place:</strong> <?= htmlspecialchars($row['place']) ?> |
            <strong>Contact:</strong> <?= htmlspecialchars($row['contact']) ?> |
            <strong>Uploaded by:</strong> <?= htmlspecialchars($row['username'] ?? 'N/A') ?>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p style="text-align:center;">No donor details found.</p>
<?php endif; ?>
</div>

</div>
<div class="footer">&copy; 2025 BloodLink-Connect Designed by THILAK NALLA</div>
<script src="script.js"></script>
</body>
</html>
