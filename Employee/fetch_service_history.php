<?php
include '../config/dbconnection.php';

$vehicleType = isset($_POST['vehicleType']) ? $_POST['vehicleType'] : '';
$mechanic = isset($_POST['mechanic']) ? $_POST['mechanic'] : '';
$date = isset($_POST['date']) ? $_POST['date'] : '';

$sql = "SELECT 
    a.app_id AS AppointmentID,
    a.status AS AppointmentStatus,
    v.license_no AS VehicleNumber,
    v.category AS VehicleType,
    CONCAT(o.fname, ' ', o.lname) AS MechanicName,
    v.cus_id AS CustomerID,
    s.service_type AS ServiceType,
    i.date AS ServiceDate,
    i.amount AS TotalPrice
FROM 
    Appointment a
JOIN 
    Vehicle v ON a.vehicle_id = v.vehicle_id
JOIN 
    Officer o ON a.officer_id = o.officer_id
JOIN 
    Service s ON a.activity_type = s.service_id
JOIN 
    activities ac ON a.app_id = ac.app_id
JOIN 
    Invoice i ON ac.invoice_id = i.invoice_id
WHERE 
    a.status = 'completed';
";

$params = [];
$types = '';

if (!empty($vehicleType)) {
    $sql .= " AND v.category = ?";
    $params[] = $vehicleType;
    $types .= 's';
}

if (!empty($mechanic)) {
    $sql .= " AND CONCAT(o.fname, ' ', o.lname) = ?";
    $params[] = $mechanic;
    $types .= 's';
}

if (!empty($date)) {
    $sql .= " AND sr.date = ?";
    $params[] = $date;
    $types .= 's';
}

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$serviceHistory = [];

while ($row = $result->fetch_assoc()) {
    $serviceHistory[] = $row;
}

echo json_encode(["status" => "success", "data" => $serviceHistory]);
?>