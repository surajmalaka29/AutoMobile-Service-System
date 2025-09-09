<?php
session_start();
include('../../config/dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activityId = $_POST['activity_id'];

    // Define the status workflow
    $statuses = [
        'Not Started' => 'Started',
        'Started' => 'Preparing Quotation',
        'Preparing Quotation' => 'Quotation Sent',
        'Quotation Sent' => 'Service Scheduled',
        'Service Scheduled' => 'Service Completed',
        'Service Completed' => 'Feedback Received'
    ];

    // Fetch the current status from the database
    $query = "SELECT status FROM activities WHERE activity_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $activityId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $currentStatus = $row['status'];
        $nextStatus = $statuses[$currentStatus] ?? null;

        if ($nextStatus) {
            // Update the status in the database
            $updateQuery = "UPDATE activities SET status = ? WHERE activity_id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("ss", $nextStatus, $activityId);
            if ($updateStmt->execute()) {
                echo json_encode(['success' => true, 'next_status' => $nextStatus]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update status.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'No next status available.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Activity not found.']);
    }
}
?>