<?php
session_start();
include('../../config/dbconnection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $activityId = $_POST['activity_id'];
    $nextStatus = 'Rejected';

    // Update the status in the database
    $updateQuery = "UPDATE activities SET status = ? WHERE activity_id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param("ss", $nextStatus, $activityId);
    $updateStmt->execute();

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

    // Update the appointment table status to 'Completed' using MySQLi
    $stmt = $conn->prepare("UPDATE appointment SET status = 'Completed' WHERE app_id = ?");
    $stmt->bind_param("i", $appointmentId);
    $stmt->execute();

    // Deduct inventory
    $searchQuery = "SELECT * FROM inventoryinvoice WHERE activity_id = ?";
    $stmt = $conn->prepare($searchQuery);
    $stmt->bind_param("i", $activityId);
    $stmt->execute();
    $invoiceResult = $stmt->get_result();
    $stmt->close();

    while ($row = $invoiceResult->fetch_assoc()) {
        $item_id = $row['item_id'];
        $quantity = $row['quantity'];

        $itemQuery = "SELECT quantity FROM inventory WHERE item_id = ?";
        $itemstmt = $conn->prepare($itemQuery);
        $itemstmt->bind_param("i", $item_id);
        $itemstmt->execute();
        $inventoryResult = $itemstmt->get_result();
        $itemstmt->close();

        if ($inventoryResult->num_rows > 0) {
            $inventoryRow = $inventoryResult->fetch_assoc();
            $inventoryQuantity = $inventoryRow['quantity'];
            $newQuantity = $inventoryQuantity + $quantity;

            if ($newQuantity < 0) {
                echo json_encode(['success' => false, 'message' => "Insufficient inventory for item ID $item_id."]);
                exit;
            }

            $update2Query = "UPDATE inventory SET quantity = ? WHERE item_id = ?";
            $update2Stmt = $conn->prepare($update2Query);
            $update2Stmt->bind_param("ii", $newQuantity, $item_id);
            $update2Stmt->execute();
            $update2Stmt->close();
        }
    }

    // delete qquery
    $deleteQuery = "DELETE inventoryinvoice WHERE activity_id = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("s", $activityId);
    $deleteStmt->execute();

}
