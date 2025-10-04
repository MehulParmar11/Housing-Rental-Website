<?php
// Start session at the beginning
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "housing_rental";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch property details based on the ID from the URL
if (isset($_GET['id'])) {
    $property_id = intval($_GET['id']); // Sanitize the property ID to ensure it's an integer
    $sql = "SELECT * FROM property WHERE property_id = $property_id";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // If property exists, fetch its details
        $property = $result->fetch_assoc();
    } else {
        echo "Property not found.";
        exit;
    }
} else {
    echo "Invalid property ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Details</title>
</head>
<body>
    <h1>Property Details</h1>

    <!-- Display Property Information -->
    <h2><?php echo htmlspecialchars($property['title']); ?></h2>
    <p><strong>Owner:</strong> <?php echo htmlspecialchars($property['owner_name']); ?></p>
    <p><strong>Title:</strong> <?php echo htmlspecialchars($property['title']); ?></p>
    <p><strong>Description:</strong> <?php echo htmlspecialchars($property['description']); ?></p>
    <p><strong>Property Type:</strong> <?php echo htmlspecialchars($property['property_type']); ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($property['location']); ?></p>
    <p><strong>Price:</strong> ₹<?php echo htmlspecialchars($property['price']); ?></p>
    <p><strong>Size:</strong> <?php echo htmlspecialchars($property['size']); ?> sq ft</p>
    <p><strong>Furnished Status:</strong> <?php echo htmlspecialchars($property['furnished_status']); ?></p>
    <p><strong>Availability Status:</strong> <?php echo htmlspecialchars($property['availability_status']); ?></p>
    <p><strong>Contact Info:</strong> <?php echo htmlspecialchars($property['contact_info']); ?></p>
    <p><strong>Pets Allowed:</strong> <?php echo htmlspecialchars($property['pets_allowed']); ?></p>
    <p><strong>Lease Terms:</strong> <?php echo htmlspecialchars($property['lease_terms']); ?></p>

    <!-- Always Show Book Now Button -->
    <form action="payment.php" method="POST">
    <input type="hidden" name="property_id" value="<?php echo htmlspecialchars($property['property_id']); ?>">
    <input type="hidden" name="price" value="<?php echo htmlspecialchars($property['price']); ?>">

    <!-- Assuming tenant_id is stored in session -->
    <input type="hidden" name="user_id" value="<?php echo isset($_SESSION['user_id']) ? htmlspecialchars($_SESSION['user_id']) : '0'; ?>">

    <button type="submit">Book Now</button> 
</form>
    <br>
    <a href="property-listings.php">Back to Listings</a>

</body>
</html>

<?php
$conn->close();
?>
