<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "housing_rental";

// Create a connection to the MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare a statement to fetch all property data
$sql = "SELECT * FROM property";  // Fetch all properties from the database
$result = $conn->query($sql);

// Check if there are properties
if ($result->num_rows > 0) {
    // Loop through all properties and store them in an array
    $properties = [];
    while ($row = $result->fetch_assoc()) {
        // Check if 'furnished_status' is empty or invalid and set a default value
        $row['furnished_status'] = ($row['furnished_status'] == NULL || $row['furnished_status'] == '') ? 'Not Specified' : $row['furnished_status'];

        // Add property to the properties array
        $properties[] = $row;
    }
} else {
    // If no properties found
    $properties = [];
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Properties</title>
</head>
<body>

<h2>All Properties</h2>

<?php if (count($properties) > 0): ?>
    <ul>
        <?php foreach ($properties as $property): ?>
            <li>
                <p><strong>Property Id:</strong> <?php echo htmlspecialchars($property['property_id']); ?></p>
                <p><strong>Owner Name:</strong> <?php echo htmlspecialchars($property['owner_name']); ?></p>
                <p><strong>Title:</strong> <?php echo htmlspecialchars($property['title']); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($property['description']); ?></p>
                <p><strong>Property Type:</strong> <?php echo htmlspecialchars($property['property_type']); ?></p>
                <p><strong>Location:</strong> <?php echo htmlspecialchars($property['location']); ?></p>
                <p><strong>Price:</strong> <?php echo htmlspecialchars($property['price']); ?></p>
                <p><strong>Size (sq. ft.):</strong> <?php echo htmlspecialchars($property['size']); ?></p>
                <p><strong>Furnished Status:</strong> <?php echo htmlspecialchars($property['furnished_status']); ?></p>
                <p><strong>Availability Status:</strong> <?php echo htmlspecialchars($property['availability_status']); ?></p>
                <p><strong>Contact Info:</strong> <?php echo htmlspecialchars($property['contact_info']); ?></p>
                <p><strong>Pets Allowed:</strong> <?php echo htmlspecialchars($property['pets_allowed']); ?></p>
                <p><strong>Lease Terms:</strong> <?php echo htmlspecialchars($property['lease_terms']); ?></p>
                
                <!-- Edit Button -->
                <p><a href="update-property.php?property_id=<?php echo $property['property_id']; ?>">Edit Property</a></p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No properties found.</p>
<?php endif; ?>

</body>
</html>
