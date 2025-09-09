<?php
session_start();
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);

require '../vendor/autoload.php';
include '../middleware/mechanicmiddleware.php';
include '../config/dbconnection.php';

use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if(isset($data['activity_id'])) {
    $invoiceId = $data['activity_id'];

    $billQuery = "
    SELECT CONCAT(c.fname, ' ', c.lname) AS customer_name, c.email, v.license_no, s.service_type, s.service_charge
    FROM activities a
    JOIN appointment ap ON a.app_id = ap.app_id
    JOIN service s ON ap.activity_type = s.service_id
    JOIN vehicle v ON ap.vehicle_id = v.vehicle_id
    JOIN customer c ON v.cus_id = c.cus_Id
    WHERE a.activity_id = ?";

    $stmt = $conn->prepare($billQuery);
    $stmt->bind_param('i', $invoiceId);
    $stmt->execute();
    $invoice = $stmt->get_result()->fetch_assoc();

    $customerName = $invoice['customer_name'];
    $customerEmail = $invoice['email'];
    date_default_timezone_set('Asia/Colombo');
    $generatedDate = date('Y-m-d');
    $serviceType = $invoice['service_type'];
    $servicePrice = $invoice['service_charge'];

    // Fetch bill charges
    $chargesQuery = "
    SELECT i.item_name, v.total_price, v.quantity
    FROM activities a
    JOIN inventoryinvoice v ON a.activity_id = v.activity_id
    JOIN inventory i ON v.item_id = i.item_id
    WHERE a.activity_id = ?";
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

    $total = array_reduce($items, fn($carry, $item) => $carry + $item['quantity'] * $item['unitPrice'], 0);

    $fullname = $customerName;
    $email = $customerEmail;
    $subject = "Service Quotation";

    $mail = new PHPMailer(true);

    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication

        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->Username   = 'pasindu2705@gmail.com';                     //SMTP username
        $mail->Password   = 'pyvj aodt yjzk fagd';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption - ENCRYPTION_SMTPS port 465
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('pasindu2705@gmail.com', 'Cras Auto');
        $mail->addAddress($email, $fullname);     //Add a recipient

        /*//Attachments
        $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name*/

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Service Quotation';

        // Generate the dynamic table rows in PHP
        $tableRows = '';
        foreach ($items as $item) {
            $tableRows .= "<tr>
                                <td>" . htmlspecialchars($item['name']) . "</td>
                                <td>" . htmlspecialchars($item['quantity']) . "</td>
                                <td>" . '$' . number_format($item['unitPrice'], 2) . "</td>
                                <td>" . 'Rs.' . number_format($item['quantity'] * $item['unitPrice'], 2) . "</td>
                            </tr>";
        }

        // Generate the grand total row
        $grandTotal = '$' . number_format($total, 2);

        // Now, build the email body
        $emailBody = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Bill Invoice</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    background-color: #f4f4f4;
                }
                .email-container {
                    max-width: 600px;
                    margin: 20px auto;
                    background-color: #ffffff;
                    border: 1px solid #dddddd;
                    border-radius: 8px;
                    overflow: hidden;
                }
                .email-header {
                    background-color: #72b4a7;
                    color: #ffffff;
                    padding: 20px;
                    text-align: center;
                }
                .email-body {
                    padding: 20px;
                    color: #333333;
                }
                .email-footer {
                    background-color: #f4f4f4;
                    text-align: center;
                    padding: 10px;
                    font-size: 12px;
                    color: #777777;
                }
                .bill-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                }
                .bill-table th, .bill-table td {
                    border: 1px solid #dddddd;
                    padding: 10px;
                    text-align: left;
                }
                .bill-table th {
                    background-color: #72b4a7;
                    color: #ffffff;
                }
                .total {
                    font-weight: bold;
                    color: #333333;
                }
            </style>
        </head>
        <body>
            <div class='email-container'>
                <div class='email-header'>
                    <h1>Service Quotation</h1>
                </div>
                <div class='email-body'>
                    <p>Dear $fullname,</p>
                    <p>Thank you for using our service. Below is the Quotation of your Service:</p>
                    <table class='bill-table'>
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            $tableRows
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan='3' class='total'>Grand Total</td>
                                <td class='total'>$grandTotal</td>
                            </tr>
                        </tfoot>
                    </table>
                    <p>If you have any questions, feel free to contact us at crasauto@gmail.com.</p>
                </div>
                <div class='email-footer'>
                    <p>&copy; 2025 Cras Auto. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>";

        $mail->Body    = $emailBody;

        if($mail->send()) {
            echo json_encode(['success' => true, 'message' => 'Email is sent successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Email is not sent']);
        }

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}