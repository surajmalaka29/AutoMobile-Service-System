<?php
include('../../config/dbconnection.php');

if (isset($_POST['query'])) {
    $query = $_POST['query'];
    $stmt = $conn->prepare("SELECT item_id, item_name, unit_price, quantity FROM inventory WHERE item_name LIKE ?");
    $searchTerm = "%{$query}%";
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $items = [];
    
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    
    echo json_encode($items);
}
?>