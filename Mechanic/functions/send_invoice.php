<?php
include('../../config/dbconnection.php');

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$date = $data['date'];
$activity_id = $data['activity_id'];
$invoices = $data['invoices'];

$response = ['success' => true];
$errorMessages = [];

// Insert invoices
foreach ($invoices as $invoice) {
    $item_id = $invoice['item_id'];
    $quantity = $invoice['quantity'];
    $unit_price = $invoice['unit_price'];
    $total_price = $quantity * $unit_price;

    $stmt = $conn->prepare("INSERT INTO inventoryinvoice (date, item_id, quantity, total_price, activity_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("siids", $date, $item_id, $quantity, $total_price, $activity_id);

    if (!$stmt->execute()) {
        $response['success'] = false;
        $errorMessages[] = "Failed to insert item ID $item_id: " . $stmt->error;
    }

    $stmt->close();
}

// Fetch customer and vehicle details
$stmt = $conn->prepare("
    SELECT v.cus_id, v.license_no, v.category
    FROM activities a
    JOIN appointment ap ON a.app_id = ap.app_id
    JOIN vehicle v ON ap.vehicle_id = v.vehicle_id
    WHERE a.activity_id = ?");
$stmt->bind_param("i", $activity_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Activity or user not found.']);
    exit;
}

// Deduct inventory
$searchQuery = "SELECT * FROM inventoryinvoice WHERE activity_id = ?";
$stmt = $conn->prepare($searchQuery);
$stmt->bind_param("i", $activity_id);
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
        $newQuantity = $inventoryQuantity - $quantity;

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

// Send notification
$customerId = $result['cus_id'];
$vehicle = $result['license_no'];
$category = $result['category'];
date_default_timezone_set('Asia/Colombo');
$notificationDate = date('Y-m-d H:i:s');
$message = "Your Quotation has been sent for your $category with the license plate number $vehicle.";

$stmt = $conn->prepare("INSERT INTO notification (date, description, status, delete_status, cus_id) VALUES (?, ?, 0, 0, ?)");
$stmt->bind_param("ssi", $notificationDate, $message, $customerId);

if (!$stmt->execute()) {
    $response['success'] = false;
    $errorMessages[] = "Failed to insert notification: " . $stmt->error;
}

$stmt->close();
$conn->close();

if (!empty($errorMessages)) {
    $response['message'] = implode(", ", $errorMessages);
}

echo json_encode($response);
?>