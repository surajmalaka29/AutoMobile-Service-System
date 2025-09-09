<?php
    include '../../config/dbconnection.php';

    $itemName = isset($_GET['itemName']) ? $_GET['itemName'] : '';

    if ($itemName) {
      // Add '%' before and after the item name to allow partial matches
      $sql = "SELECT item_name FROM inventory WHERE item_name LIKE ?";
      $stmt = $conn->prepare($sql);
      
      // Use '%' before and after the item name for partial matching
      $searchTerm = "%" . $itemName . "%";
      $stmt->bind_param('s', $searchTerm);
      $stmt->execute();
      $result = $stmt->get_result();
    
      $names = [];
      while ($row = $result->fetch_assoc()) {
        $names[] = $row['item_name'];
      }
    
      echo json_encode($names);
    }
?>