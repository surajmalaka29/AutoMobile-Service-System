<?php
include '../../config/dbconnection.php';

if (isset($_GET['service_id'])) {
    $service_id = $_GET['service_id'];
    $search_query = "SELECT * FROM service WHERE service_id = ?";
    $stmt = $conn->prepare($search_query);
    $stmt->bind_param("i", $service_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $serviceInfo = $result->fetch_assoc();
        echo json_encode($serviceInfo);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No record found']);
    }
}