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

// Get the property_id from the URL and ensure it's an integer
$property_id = isset($_GET['property_id']) ? (int) $_GET['property_id'] : 0;

// If no valid property_id is provided, show an error message
if ($property_id <= 0) {
    die("Invalid Property ID.");
}

// Prepare a statement to fetch the property data safely
$sql = "SELECT * FROM property WHERE property_id = ?";
$stmt = $conn->prepare($sql);

// Check if the statement was prepared successfully
if ($stmt === false) {
    die("Error preparing the SQL statement: " . $conn->error);
}

// Bind the property_id parameter to the statement
$stmt->bind_param("i", $property_id);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if the property exists
if ($result->num_rows > 0) {
    // Fetch the property data
    $property = $result->fetch_assoc();
} else {
    echo "Property not found.";
    exit();
}

// Close the statement
$stmt->close();

// Handle form submission for updating property
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $name = $_POST['name'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $property_type = $_POST['property_type'];
    $location = $_POST['location'];
    $price = $_POST['price'];
    $size = $_POST['size'];
    $furnished_status = $_POST['furnished_status'];

    // Prepare the update query
    $sql = "UPDATE property SET owner_name=?, title=?, description=?, property_type=?, location=?, price=?, size=?, furnished_status=? WHERE property_id=?";
    $stmt = $conn->prepare($sql);
    
    // Bind the parameters to the query
    $stmt->bind_param("ssssssssi", $name, $title, $description, $property_type, $location, $price, $size, $furnished_status, $property_id);



    // Execute the query
    if ($stmt->execute()) {
        // Redirect to the view-properties page after successful update
        header("Location: view-property.php");
        exit();
    } else {
        echo "Error updating property: " . $conn->error;
    }

    // Close the statement
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Property</title>
</head>
<body>

<h2>Update Property</h2>

<!-- Update Property Form -->
<form method="POST" action="">
    <!-- Owner Name -->
    <label for="name">Owner Name:</label>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($property['owner_name']); ?>" required>

    <!-- Title -->
    <label for="title">Property Title:</label>
    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($property['title']); ?>" required>

    <!-- Description -->
    <label for="description">Description:</label>
    <textarea id="description" name="description" rows="4" required><?php echo htmlspecialchars($property['description']); ?></textarea>

    <!-- Property Type -->
    <label for="property_type">Property Type:</label>
    <select id="property_type" name="property_type" required>
        <option value="Flat" <?php if ($property['property_type'] == 'Flat') echo 'selected'; ?>>Flat</option>
        <option value="Villa" <?php if ($property['property_type'] == 'Villa') echo 'selected'; ?>>Villa</option>
        <option value="Duplex" <?php if ($property['property_type'] == 'Duplex') echo 'selected'; ?>>Duplex</option>
        <option value="Bungalow" <?php if ($property['property_type'] == 'Bungalow') echo 'selected'; ?>>Bungalow</option>
    </select>

    <!-- Location -->
    <label for="location">Location:</label>
    <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($property['location']); ?>" required>

    <!-- Price -->
    <label for="price">Price:</label>
    <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($property['price']); ?>" required>

    <!-- Size -->
    <label for="size">Size (sq. ft.):</label>
    <input type="number" id="size" name="size" value="<?php echo htmlspecialchars($property['size']); ?>" required>

    <!-- Furnished Status -->
    <label for="furnished_status">Furnished_status:</label>
    <input type="text" id="furnished_status" name="furnished_status" value="<?php echo htmlspecialchars($property['furnished_status']); ?>" required>

    <!-- Submit Button -->
    <button type="submit">Update Property</button>
</form>

</body>
</html>
