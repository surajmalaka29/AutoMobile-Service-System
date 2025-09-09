<?php
    include '../../config/dbconnection.php';

    $service = isset($_GET['service']) ? $_GET['service'] : '';

    if ($service) {
      $sql = "SELECT service_type FROM service WHERE service_type LIKE ?";
      $stmt = $conn->prepare($sql);
      $searchTerm = "%" . $service . "%";
      $stmt->bind_param('s', $searchTerm);
      $stmt->execute();
      $result = $stmt->get_result();
    
      $names = [];
      while ($row = $result->fetch_assoc()) {
        $names[] = $row['service_type'];
      }
    
      echo json_encode($names);
    }
?>