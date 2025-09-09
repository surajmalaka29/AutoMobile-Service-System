<?php
    include '../../config/dbconnection.php';

    if (isset($_POST['service_record_id'])) {
        $service_record_id = $_POST['service_record_id'];

        $stmt = $conn->prepare("SELECT 
                                    a.activity_id,
                                    ap.app_date AS date,
                                    ap.app_time AS time,
                                    v.category,
                                    v.license_no,
                                    o.fname,
                                    o.lname,
                                    ap.message AS description
                                FROM 
                                    activities a
                                JOIN 
                                    appointment ap ON a.app_id = ap.app_id
                                JOIN 
                                    vehicle v ON ap.vehicle_id = v.vehicle_id
                                JOIN 
                                    officer o ON ap.officer_id = o.officer_id
                                WHERE 
                                    a.activity_id = ?");
                                    $stmt->bind_param("i", $service_record_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $serviceRecord = $result->fetch_assoc();
            echo json_encode($serviceRecord);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No record found']);
        }
    }
?>