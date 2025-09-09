<?php
session_start();
include('../../config/dbconnection.php');

// Set response header to JSON
header('Content-Type: application/json');

// Check if POST request is made
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activityId = $_POST['activity_id'] ?? null;

    if (!$activityId) {
        // Clear any output and send JSON response
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Activity ID is required.']);
        exit;
    }

    try {
        // Fetch the corresponding appointment ID from the activity table using MySQLi
        $stmt = $conn->prepare("SELECT app_id FROM activities WHERE activity_id = ?");
        $stmt->bind_param("i", $activityId);  // "i" is for integer type
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if (!$result) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Activity not found.']);
            exit;
        }

        $appointmentId = $result['app_id'];

        $stmt = $conn->prepare("SELECT v.cus_id, v.license_no, a.activity_type FROM appointment a JOIN vehicle v ON a.vehicle_id = v.vehicle_id WHERE app_id = ?");
        $stmt->bind_param("i", $appointmentId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if (!$result) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'User not found.']);
            exit;
        }

        $customerId = $result['cus_id'];
        $licenseNo = $result['license_no'];
        $serviceId = $result['activity_type'];
        date_default_timezone_set('Asia/Colombo');
        $date = date('Y-m-d H:i:s');
        $status = 0;
        $deleteStatus = 0;
        $message = "Your vehicle ( " . $licenseNo . " ) Service completed successfully.";

        // Send a Notification to the customer
        $stmt = $conn->prepare("INSERT INTO notification (date, description, status, delete_status, cus_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiii", $date, $message, $status, $deleteStatus, $customerId);
        $stmt->execute();

        // Fetch the service charge from the service table using MySQLi
        $stmt = $conn->prepare("SELECT service_charge FROM service WHERE service_id = ?");
        $stmt->bind_param("i", $serviceId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        if (!$result) {
            ob_end_clean();
            echo json_encode(['success' => false, 'message' => 'Service not found.']);
            exit;
        }

        $servicePrice = $result['service_charge'];

        // Adding the all inventory charges to the invoice
        $chargesQuery = "
            SELECT i.item_name, v.total_price, v.quantity
            FROM activities a
            JOIN inventoryinvoice v ON a.activity_id = v.activity_id
            JOIN inventory i ON v.item_id = i.item_id
            WHERE a.activity_id = ?";
        $stmt = $conn->prepare($chargesQuery);
        $stmt->bind_param('i', $activityId);
        $stmt->execute();
        $chargesResult = $stmt->get_result();

        // Initialize total price with the service price
        $totalPrice = $servicePrice;

        // Add the prices of items from the query
        while ($item = $chargesResult->fetch_assoc()) {
            $totalPrice += $item['total_price'];
        }

        date_default_timezone_set('Asia/Colombo');
        $date = date('Y-m-d');
        $officerId = $_SESSION['auth_user']['userId'];
        $amount = $totalPrice;
        $state = 'UnPaid';

        // Create an invoice for the customer
        $stmt = $conn->prepare("INSERT INTO invoice (date, amount, status, officer_id, cus_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sdsii", $date, $amount, $state, $officerId, $customerId);
        //$stmt->execute();
        if ($stmt->execute()) {
            $lastId = $conn->insert_id;
        }

        // Update the activity table status to 'Completed' using MySQLi
        $stmt = $conn->prepare("UPDATE activities SET status = 'Completed', invoice_id = ?, endDate = NOW() WHERE activity_id = ?");
        $stmt->bind_param("ii", $lastId, $activityId);
        $stmt->execute();

        // Update the appointment table status to 'Completed' using MySQLi
        $stmt = $conn->prepare("UPDATE appointment SET status = 'Completed' WHERE app_id = ?");
        $stmt->bind_param("i", $appointmentId);
        $stmt->execute();

        // Clear any output and send JSON response
        ob_end_clean();
        echo json_encode(['success' => true, 'message' => 'Status updated successfully.']);
    } catch (Exception $e) {
        ob_end_clean();
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}