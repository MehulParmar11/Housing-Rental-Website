<?php
// Include the database connection
include "conn.php"; // Database connection file

// Start the session to check for logged-in user
session_start();

// Get the property and tenant details from the POST data (hidden inputs from form submission)
if (isset($_POST['property_id']) && isset($_POST['tenant_id']) && isset($_POST['price'])) {
    $property_id = $_POST['property_id'];  // Property ID
    $tenant_id = $_POST['tenant_id'];      // Tenant/User ID (this is the logged-in user ID)
    $amount = $_POST['price'];             // Property Price (Amount)
} else {
    echo "Error: Missing property details.";
    exit();
}

// Check if the tenant is logged in and validate the session
if (!isset($_SESSION['tenant_id']) || $_SESSION['tenant_id'] != $tenant_id) {
    echo "Error: Invalid tenant session.";
    exit();
}

// Check if the form is submitted (payment method selected)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure the payment method is selected
    if (isset($_POST['payment_method'])) {
        $payment_method = $_POST['payment_method'];

        // Declare variables to store the payment details
        $card_number = isset($_POST['card_number']) ? $_POST['card_number'] : '';
        $expiry = isset($_POST['expiry']) ? $_POST['expiry'] : '';
        $cvv = isset($_POST['cvv']) ? $_POST['cvv'] : '';
        $bank_name = isset($_POST['bank_name']) ? $_POST['bank_name'] : '';
        $account_number = isset($_POST['account_number']) ? $_POST['account_number'] : '';
        $ifsc_code = isset($_POST['ifsc_code']) ? $_POST['ifsc_code'] : '';
        $upi_id = isset($_POST['upi_id']) ? $_POST['upi_id'] : '';

        // Set the initial payment status to 'Pending'
        $payment_status = 'Pending';
        $transaction_id = ''; // This can be generated or retrieved from a payment gateway

        // Process the payment and insert payment details into the database
        if ($payment_method == 'credit_card' || $payment_method == 'debit_card') {
            // Validate card details
            if (empty($card_number) || empty($expiry) || empty($cvv)) {
                echo "Error: Missing card details.";
                exit();
            }

            $sql = "INSERT INTO payment (tenant_id, property_id, amount, payment_method, card_number, expiry_date, cvv, payment_status, transaction_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiissssss", $tenant_id, $property_id, $amount, $payment_method, $card_number, $expiry, $cvv, $payment_status, $transaction_id);

        } elseif ($payment_method == 'net_banking') {
            // Validate net banking details
            if (empty($bank_name) || empty($account_number) || empty($ifsc_code)) {
                echo "Error: Missing net banking details.";
                exit();
            }

            $sql = "INSERT INTO payment (tenant_id, property_id, amount, payment_method, bank_name, account_number, ifsc_code, payment_status, transaction_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiissssss", $tenant_id, $property_id, $amount, $payment_method, $bank_name, $account_number, $ifsc_code, $payment_status, $transaction_id);

        } elseif ($payment_method == 'upi') {
            // Validate UPI details
            if (empty($upi_id)) {
                echo "Error: Missing UPI ID.";
                exit();
            }

            $sql = "INSERT INTO payment (tenant_id, property_id, amount, payment_method, upi_id, payment_status, transaction_id) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiissss", $tenant_id, $property_id, $amount, $payment_method, $upi_id, $payment_status, $transaction_id);
        } else {
            echo "Error: Invalid payment method.";
            exit();
        }

        // Execute the payment insertion query
        if ($stmt->execute()) {
            // On successful payment, update payment status to 'Completed' (this would normally be done after verifying with payment gateway)
            // Example: Update payment status after successful gateway response
            // $transaction_id = generateTransactionId();  // This should be returned by your payment gateway
            $payment_status = 'Completed'; // For now, set status to 'Completed' as if the payment went through
            $update_sql = "UPDATE payment SET payment_status = ?, transaction_id = ? WHERE tenant_id = ? AND property_id = ? AND payment_status = 'Pending'";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ssii", $payment_status, $transaction_id, $tenant_id, $property_id);
            $update_stmt->execute();

            echo "<script>alert('Payment successful!'); window.location.href = 'property-listings.php';</script>";
            exit();
        } else {
            echo "Error processing payment: " . $conn->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Error: Payment method not selected.";
        exit();
    }
}

// Close the database connection
$conn->close();
?>
