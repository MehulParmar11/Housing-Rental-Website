<?php
session_start(); // Start session to store success message
include "conn.php";

// Handle form submission for deleting a property
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_property_id'])) {
    $property_id_to_delete = (int) $_POST['delete_property_id'];

    // Prepare the delete query
    $sql = "DELETE FROM property WHERE property_id = ?";
    $stmt = $conn->prepare($sql);

    // Bind the property_id parameter to the query
    $stmt->bind_param("i", $property_id_to_delete);

    // Execute the delete query
    if ($stmt->execute()) {
        // Set success message in session
        $_SESSION['message'] = "Property deleted successfully!";
        
        // Redirect to this page after deletion to show updated list
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error deleting property: " . $conn->error;
    }

    // Close the statement
    $stmt->close();
}

// Fetch all properties from the database
$sql = "SELECT * FROM property";
$result = $conn->query($sql);

// Store properties in an array if found
$properties = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Properties</title>
    <link rel="stylesheet" href="style-properties.css"> <!-- Link to CSS file -->
</head>
<body>

<div class="container">
    <h2>All Properties</h2>

    <!-- Display any success message -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="message">
            <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']); // Clear message after displaying it
            ?>
        </div>
    <?php endif; ?>

    <!-- Display properties -->
    <?php if (!empty($properties)): ?>
        <table>
            <thead>
                <tr>
                    <th>Owner Name</th>
                    <th>Property Title</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Price</th>
                    <th>Size (sq. ft.)</th>
                    <th>Furnished Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($properties as $property): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($property['owner_name']); ?></td>
                        <td><?php echo htmlspecialchars($property['title']); ?></td>
                        <td><?php echo htmlspecialchars($property['description']); ?></td>
                        <td><?php echo htmlspecialchars($property['location']); ?></td>
                        <td><?php echo htmlspecialchars($property['price']); ?></td>
                        <td><?php echo htmlspecialchars($property['size']); ?></td>
                        <td><?php echo htmlspecialchars($property['furnished_status']); ?></td>
                        <td>
                            <form method="POST" action="" class="delete-form">
                                <input type="hidden" name="delete_property_id" value="<?php echo $property['property_id']; ?>">
                                <button type="submit" class="delete-button" onclick="return confirm('Are you sure you want to delete this property?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-properties">No properties available to display.</p>
    <?php endif; ?>
</div>

</body>
</html>
