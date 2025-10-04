<?php
include 'db.php';

// Fetch all donors ordered by id ascending (so last one is recent)
$result = $conn->query("SELECT name, blood FROM donor ORDER BY id ASC");

$donors = [];
if($result){
    while($row = $result->fetch_assoc()){
        $donors[] = $row;
    }
}

// Return JSON
header('Content-Type: application/json');
echo json_encode($donors);
?>
