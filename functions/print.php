<?php
require_once '../vendor/autoload.php';
require_once '../config/dbconnection.php';

$invoiceId = $_GET['invoice_id'];

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
$stmt->bind_param('i', $invoiceId);
$stmt->execute();
$invoice = $stmt->get_result()->fetch_assoc();

$customerName = $invoice['customer_name'];
$customerContact = $invoice['phone'];
$customerEmail = $invoice['email'];
$invoiceNumber = sprintf('%04d', $invoiceId);
$billingDate = $invoice['date'];
$serviceType = $invoice['service_type'];
$servicePrice = $invoice['service_charge'];
date_default_timezone_set('Asia/Colombo');
$generatedDate = date('Y-m-d');


// Fetch bill charges
$chargesQuery = "
SELECT i.item_name, v.total_price, v.quantity
FROM activities a
JOIN inventoryinvoice v ON a.activity_id = v.activity_id
JOIN inventory i ON v.item_id = i.item_id
WHERE invoice_id = ?";
$stmt = $conn->prepare($chargesQuery);
$stmt->bind_param('i', $invoiceId);
$stmt->execute();
$chargesResult = $stmt->get_result();

$items = [
    [
        "name" => $serviceType,
        "quantity" => 1,
        "unitPrice" => $servicePrice
    ]
];
while ($item = $chargesResult->fetch_assoc()) {
    $items[] = [
        "name" => $item['item_name'],
        "quantity" => $item['quantity'],
        "unitPrice" => $item['total_price']
    ];
}

// Calculate total dynamically
$total = array_reduce($items, fn($carry, $item) => $carry + $item['quantity'] * $item['unitPrice'], 0);

$html = "
<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Bill</title>
</head>
<body style='font-family: Arial, sans-serif; margin: 0; padding: 0;'>
    <div style='width: 100%; padding: 20px; box-sizing: border-box;'>
        <!-- Header -->
        <table style='width: 100%; border-bottom: 2px solid #72b4a7; margin-bottom: 20px;'>
            <tr>
                <td style='width: 150px;'>
                    <img src='assets/img/bill_logo.png' alt='Company Logo' style='max-width: 150px;'>
                </td>
                <td style='text-align: right;'>
                    <h2 style='margin: 0; color: #72b4a7;'>Cras Auto</h2>
                    <p style='margin: 0;'>
                        No.123, Main St,<br>
                        Negombo<br>
                        076 6101663<br>
                        crasauto.com
                    </p>
                </td>
            </tr>
        </table>

        <!-- Subheader -->
        <table style='width: 100%; margin-bottom: 20px;'>
            <tr>
                <td style='text-align: left;'>
                    <p style='margin: 0;'>Invoice issued for</p>
                    <p style='margin: 0; margin-left: 15px;'>
                        <span style='font-weight: bold; font-size: large;'>$customerName</span><br>
                        $customerContact<br>
                        $customerEmail
                    </p>
                </td>
                <td style='text-align: right;'>
                    <h2 style='margin: 0;'>Invoice #: $invoiceNumber</h2>
                    <p style='margin: 0;'>Billing Date: $billingDate<br>
                    Generated Date: $generatedDate</p>
                </td>
            </tr>
        </table>

        <!-- Bill Details -->
        <h3>Bill Details</h3>
        <table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>
            <thead>
                <tr>
                    <th style='border: 1px solid #ddd; padding: 8px; background-color: #72b4a7; color: white;'>#</th>
                    <th style='border: 1px solid #ddd; padding: 8px; background-color: #72b4a7; color: white;'>Item Name</th>
                    <th style='border: 1px solid #ddd; padding: 8px; background-color: #72b4a7; color: white;'>Quantity</th>
                    <th style='border: 1px solid #ddd; padding: 8px; background-color: #72b4a7; color: white;'>Unit Price</th>
                    <th style='border: 1px solid #ddd; padding: 8px; background-color: #72b4a7; color: white;'>Total</th>
                </tr>
            </thead>
            <tbody>";

foreach ($items as $index => $item) {
    $itemTotal = $item['quantity'] * $item['unitPrice'];
    $html .= "
                <tr>
                    <td style='border: 1px solid #ddd; padding: 8px;'>".($index + 1)."</td>
                    <td style='border: 1px solid #ddd; padding: 8px;'>{$item['name']}</td>
                    <td style='border: 1px solid #ddd; padding: 8px;'>{$item['quantity']}</td>
                    <td style='border: 1px solid #ddd; padding: 8px;'>Rs.".number_format($item['unitPrice'], 2)."</td>
                    <td style='border: 1px solid #ddd; padding: 8px;'>Rs.".number_format($itemTotal, 2)."</td>
                </tr>";
}

$html .= "
            </tbody>
        </table>

        <!-- Total -->
        <div style='text-align: right; font-size: 18px; margin-top: 20px;'>
            <strong>Total: Rs.$total</strong>
        </div>

        <!-- Notes -->
        <div>
            <h3 style='margin-bottom: 0;'>Invoice Notes</h3>
            <p style='margin-top: 5px;'>Thank you for choosing our services!</p>
        </div>
    </div>
</body>
</html>
";

$billName = 'Invoice ' . $invoiceId;

$mpdf = new \Mpdf\Mpdf();
$mpdf->WriteHTML($html);
$filename = 'Invoice' . $invoiceId . '.pdf';
$mpdf->Output($filename, 'D');

$_SESSION['message'] = "Bill $billName has been printed successfully!";

header( '/AutoMobile Project/User/bill.php');
exit;