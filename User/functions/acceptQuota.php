<?php
session_start();
include('../../config/dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['activity_id'])) {
    $activityId = $_POST['activity_id'];
    $nextStatus = 'Service Scheduled';

    // Update the status in the database
    $updateQuery = "UPDATE activities SET status = ? WHERE activity_id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    if ($updateStmt) {
        $updateStmt->bind_param("ss", $nextStatus, $activityId);
        $updateStmt->execute();
        $updateStmt->close();
    } else {
        die("Failed to prepare update query: " . $conn->error);
    }


    // Fetch customer and vehicle details
    $stmt = $conn->prepare("
                            SELECT ap.officer_id, v.license_no, v.category
                            FROM activities a
                            JOIN appointment ap ON a.app_id = ap.app_id
                            JOIN vehicle v ON ap.vehicle_id = v.vehicle_id
                            WHERE a.activity_id = ?");
    $stmt->bind_param("i", $activityId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Send notification
    $customerId = $result['officer_id'];
    $vehicle = $result['license_no'];
    $category = $result['category'];
    date_default_timezone_set('Asia/Colombo');
    $notificationDate = date('Y-m-d H:i:s');
    $message = "Your Quotation has been accepted by the customer for the $category with the license plate number $vehicle.";

    $stmt = $conn->prepare("INSERT INTO notification (date, description, status, delete_status, officer_id) VALUES (?, ?, 0, 0, ?)");
    $stmt->bind_param("ssi", $notificationDate, $message, $customerId);

    if (!$stmt->execute()) {
        $response['success'] = false;
        $errorMessages[] = "Failed to insert notification: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
