<?php
include '../../config/dbconnection.php';

if (isset($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];
    $search_query = "SELECT * FROM customer WHERE cus_Id = ?";
    $stmt = $conn->prepare($search_query);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $customerInfo = $result->fetch_assoc();
        echo json_encode($customerInfo);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No record found']);
    }
}