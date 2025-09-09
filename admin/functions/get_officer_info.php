<?php
include '../../config/dbconnection.php';

if (isset($_GET['officer_id'])) {
    $officer_id = $_GET['officer_id'];
    $search_query = "SELECT * FROM officer WHERE officer_id = ?";
    $stmt = $conn->prepare($search_query);
    $stmt->bind_param("i", $officer_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $officerInfo = $result->fetch_assoc();
        echo json_encode($officerInfo);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No record found']);
    }
}