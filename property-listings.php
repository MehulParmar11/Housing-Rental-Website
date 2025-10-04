<?php

include "conn.php";
// Initialize query
$sql = "SELECT * FROM property WHERE 1";

// Apply filters if user inputs city or area
if (isset($_GET['city']) && $_GET['city'] != '') {
    $city = $_GET['city'];
    $sql .= " AND location LIKE '%$city%'";
}

if (isset($_GET['area']) && $_GET['area'] != '') {
    $area = $_GET['area'];
    $sql .= " AND location LIKE '%$area%'";
}

$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Listings</title>
</head>
<body>
    <h2>Properties</h2>
    <ul>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<li>";
                echo "<strong>Location:</strong> " . $row['location'] . "<br>";
                echo "<strong>Price:</strong> ₹" . $row['price'] . "<br>";
                echo "<a href='property-details.php?id=" . $row['property_id'] . "'>View Details</a>";
                echo "</li>";
            }
        } else {
            echo "<p>No properties found matching your criteria.</p>";
        }
        ?>
    </ul>
</body>
</html>
