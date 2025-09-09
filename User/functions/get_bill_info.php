<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include ('../../config/dbconnection.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bill_no = $_POST['invoice_id'];

    // Fetch customer details and bill info
    $billQuery = "
    SELECT i.invoice_id, c.cus_id, CONCAT(c.fname, ' ', c.lname) AS customer_name, c.phone, c.email, v.license_no, i.date, i.amount, s.service_type, s.service_charge
    FROM invoice i
    JOIN activities a ON i.invoice_id = a.invoice_id
    JOIN appointment ap ON a.app_id = ap.app_id
    JOIN service s ON ap.activity_type = s.service_id
    JOIN vehicle v ON ap.vehicle_id = v.vehicle_id
    JOIN customer c ON i.cus_id = c.cus_Id
    WHERE i.invoice_id = ?";

    $stmt = $conn->prepare($billQuery);
    $stmt->bind_param('i', $bill_no);
    $stmt->execute();
    $billResult = $stmt->get_result()->fetch_assoc();

    // Fetch bill charges
    $chargesQuery = "
    SELECT i.item_name, v.total_price, v.quantity
    FROM activities a
    JOIN inventoryinvoice v ON a.activity_id = v.activity_id
    JOIN inventory i ON v.item_id = i.item_id
    WHERE invoice_id = ?";
    $stmt = $conn->prepare($chargesQuery);
    $stmt->bind_param('i', $bill_no);
    $stmt->execute();
    $chargesResult = $stmt->get_result();
    
    $charges = [];
    while ($charge = $chargesResult->fetch_assoc()) {
        $charges[] = $charge;
    }

    echo json_encode([
        'customerNo' => $billResult['cus_id'],
        'customerName' => $billResult['customer_name'],
        'customerPhone' => $billResult['phone'],
        'customerEmail' => $billResult['email'],
        'billNo' => $billResult['invoice_id'],
        'licensePlateNo' => $billResult['license_no'],
        'billingDate' => $billResult['date'],
        'total' => $billResult['amount'],
        'serviceName' => $billResult['service_type'],
        'serviceCharge' => $billResult['service_charge'],
        'charges' => $charges
    ]);
}

?>
