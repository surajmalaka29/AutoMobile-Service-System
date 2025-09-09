<?php
include '../../config/dbconnection.php';

if (isset($_GET['vehicle_id'])) {
    $vehicle_id = $_GET['vehicle_id'];
    $search_query = "SELECT * FROM vehicle WHERE vehicle_id = ?";
    $stmt = $conn->prepare($search_query);
    $stmt->bind_param("i", $vehicle_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $vehicleInfo = $result->fetch_assoc();
        echo json_encode($vehicleInfo);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No record found']);
    }
}