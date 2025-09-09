<?php
require_once '../config/dbconnection.php';

// Query to fetch invoice data
$sql = "SELECT i.invoice_id, c.fname, c.lname, i.date, i.amount, i.status FROM invoice i, customer c WHERE i.cus_id = c.cus_id";
$result = $conn->query($sql);

// Set headers to force download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=invoices.csv');

$output = fopen('php://output', 'w');
fputcsv($output, array('Invoice ID', 'Customer Name', 'Date', 'Amount', 'Status'));

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, array(
            $row['invoice_id'],
            $row['fname'] . ' ' . $row['lname'],
            $row['date'],
            $row['amount'],
            $row['status']
        ));
    }
}

fclose($output);
$conn->close();
exit();