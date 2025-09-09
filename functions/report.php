<?php
include '../config/dbconnection.php';

$month = date('Y-m'); // Format: YYYY-MM

// Total sales and profit for the month
$salesQuery = "SELECT 
    ii.item_id,
    i.item_name,
    SUM(ii.quantity) AS total_quantity,
    i.unit_buying_price,
    SUM(ii.total_price) AS total_sales,
    (SUM(ii.total_price) - (SUM(ii.quantity) * i.unit_buying_price)) AS total_profit
FROM 
    activities a
JOIN 
    inventoryinvoice ii ON a.activity_id = ii.activity_id
JOIN 
    inventory i ON ii.item_id = i.item_id
WHERE 
    a.status = 'completed'
    AND DATE_FORMAT(a.endDate, '%Y-%m') = ?
GROUP BY 
    ii.item_id, i.item_name, i.unit_buying_price;";

$stmt = $conn->prepare($salesQuery);
$stmt->bind_param('s', $month);
$stmt->execute();
$saleresult = $stmt->get_result();

// Top 5 most used items
$mostUsedItemsQuery = "SELECT 
    i.item_name,
    SUM(ii.quantity) AS total_quantity
FROM 
    activities a
JOIN 
    inventoryinvoice ii ON a.activity_id = ii.activity_id
JOIN 
    inventory i ON ii.item_id = i.item_id
WHERE 
    a.status = 'completed'
    AND DATE_FORMAT(a.endDate, '%Y-%m') = ?
GROUP BY 
    i.item_name
ORDER BY 
    total_quantity DESC
LIMIT 5;";

$stmt = $conn->prepare($mostUsedItemsQuery);
$stmt->bind_param('s', $month);
$stmt->execute();
$itemsresult = $stmt->get_result();

// Top 3 customers for the month
$customerQuery = "SELECT 
    c.cus_id,
    CONCAT(c.fname, ' ', c.lname) AS customer_name,
    COUNT(a.app_id) AS completed_appointments,
    SUM(inv.amount) AS total_amount_spent
FROM 
    customer c
JOIN 
    vehicle v ON c.cus_id = v.cus_id
JOIN 
    appointment a ON v.vehicle_id = a.vehicle_id
JOIN 
    invoice inv ON inv.cus_id = c.cus_id
WHERE 
    a.status = 'completed'
    AND DATE_FORMAT(a.date, '%Y-%m') = ?  -- Filter by appointment date for the given month
    AND DATE_FORMAT(inv.date, '%Y-%m') = ?  -- Ensure invoice is from the same month
GROUP BY 
    c.cus_id, c.fname, c.lname
ORDER BY 
    total_amount_spent DESC, completed_appointments DESC
LIMIT 3;";

$stmt = $conn->prepare($customerQuery);
$stmt->bind_param('ss', $month, $month);
$stmt->execute();
$customerresult = $stmt->get_result();

// Employee of the Month
$employeeQuery = "SELECT 
    o.officer_id,
    CONCAT(o.fname, ' ', o.lname) AS mechanic_name,
    COUNT(a.app_id) AS completed_appointments
FROM 
    officer o
JOIN 
    appointment a ON o.officer_id = a.officer_id
WHERE 
    o.role = 'mechanic'
    AND a.status = 'completed'
    AND DATE_FORMAT(a.date, '%Y-%m') = ?
GROUP BY 
    o.officer_id, o.fname, o.lname
ORDER BY 
    completed_appointments DESC
LIMIT 1;";

$stmt = $conn->prepare($employeeQuery);
$stmt->bind_param('s', $month);
$stmt->execute();
$employeeresult = $stmt->get_result();

// Generate the HTML report
$html = "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Monthly Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            width: 80%;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1, h2 {
            text-align: center;
            color: #72b4a7;
        }
        table {
            width: 100%;
            margin: 20px 0;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #72b4a7;
            color: white;
        }
        .report-section {
            margin-bottom: 40px;
        }
        .section-title {
            background-color: #72b4a7;
            color: white;
            padding: 10px;
            font-size: 1.2em;
            margin-bottom: 10px;
        }
        .footer {
            text-align: center;
            font-size: 0.9em;
            color: #666;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<div class='container'>
    <h1>Monthly Report - $month</h1>";

$html .= "
    <!-- Sales and Profit Report -->
    <div class='report-section'>
        <div class='section-title'>Sales and Profit for the Month</div>
        <table>
            <thead>
            <tr>
                <th>Item ID</th>
                <th>Item Name</th>
                <th>Total Quantity Sold</th>
                <th>Unit Buying Price</th>
                <th>Total Sales</th>
                <th>Total Profit</th>
            </tr>
            </thead>
            <tbody>";
            while ($row = mysqli_fetch_assoc($saleresult)) {
                $html .= "
                <tr>
                    <td>{$row['item_id']}</td>
                    <td>{$row['item_name']}</td>
                    <td>{$row['total_quantity']}</td>
                    <td>$" . number_format($row['unit_buying_price'], 2) . "</td>
                    <td>$" . number_format($row['total_sales'], 2) . "</td>
                    <td>$" . number_format($row['total_profit'], 2) . "</td>
                </tr>";
            }
$html .= "
            </tbody>
        </table>
    </div>";

$html .= "
    <!-- Top 5 Most Used Items -->
    <div class='report-section'>
        <div class='section-title'>Top 5 Most Used Items</div>
        <table>
            <tr>
                <th>Item Name</th>
                <th>Total Quantity Used</th>
            </tr>";
            while ($row = mysqli_fetch_assoc($itemsresult)) {
                $html .= "
                <tr>
                    <td>{$row['item_name']}</td>
                    <td>{$row['total_quantity']}</td>
                </tr>";
            }
$html .= "
        </table>
    </div>";

$html .= "
    <!-- Top 3 Customers for the Month -->
    <div class='report-section'>
        <div class='section-title'>Top 3 Customers for the Month</div>
        <table>
            <tr>
                <th>Customer Name</th>
                <th>Total Amount Spent</th>
                <th>Completed Appointments</th>
            </tr>";
            while ($row = $customerresult->fetch_assoc()) {
                $html .= "
                <tr>
                    <td>{$row['customer_name']}</td>
                    <td>$" . number_format($row['total_amount_spent'], 2) . "</td>
                    <td>{$row['completed_appointments']}</td>
                </tr>";
            }
$html .= "
        </table>
    </div>";

$html .= "
    <!-- Employee of the Month -->
    <div class='report-section'>
        <div class='section-title'>Employee of the Month</div>
        <table>
            <tr>
                <th>Employee Name</th>
                <th>Completed Appointments</th>
            </tr>";
            if ($row = $employeeresult->fetch_assoc()) {
                $html .= "
                <tr>
                    <td>{$row['mechanic_name']}</td>
                    <td>{$row['completed_appointments']}</td>
                </tr>";
            }
$html .= "
        </table>
    </div>";

$html .= "</div></body></html>";

require_once '../vendor/autoload.php';
$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$filename = 'monthly_report_' . $month . '.pdf';
$mpdf->Output($filename, 'D');
header('Location: ../reports.php');
exit;
?>