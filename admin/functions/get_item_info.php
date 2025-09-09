<?php
include '../../config/dbconnection.php';

if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];
    $search_query = "SELECT * FROM inventory WHERE item_id = ?";
    $stmt = $conn->prepare($search_query);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $itemInfo = $result->fetch_assoc();
        echo json_encode($itemInfo);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No record found']);
    }
}