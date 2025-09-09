<?php
    include '../../config/dbconnection.php';

    $customer = isset($_GET['customer']) ? $_GET['customer'] : '';

    if ($customer) {
      $sql = "SELECT CONCAT(fname, ' ', lname) AS name FROM customer WHERE fname LIKE ? OR lname LIKE ?";
      $stmt = $conn->prepare($sql);
      $searchTerm = "%" . $customer . "%";
      $stmt->bind_param('ss', $searchTerm, $searchTerm);
      $stmt->execute();
      $result = $stmt->get_result();
    
      $names = [];
      while ($row = $result->fetch_assoc()) {
        $names[] = $row['name'];
      }
    
      echo json_encode($names);
    }
?>