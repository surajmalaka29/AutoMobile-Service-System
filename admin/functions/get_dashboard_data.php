<?php
include '../../config/dbconnection.php';

// Query for appointments (doughnut chart)
$appointmentsQuery = "
    SELECT 
        DAYNAME(date) AS day, 
        COUNT(*) AS count 
    FROM appointment 
    GROUP BY DAYNAME(date)
    ORDER BY FIELD(DAYNAME(date), 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')";

$appointmentsResult = $conn->query($appointmentsQuery);
$appointmentsData = [];
while ($row = $appointmentsResult->fetch_assoc()) {
    $appointmentsData[$row['day']] = $row['count'];
}

// Query for invoices (line chart)
$invoicesQuery = "
    SELECT 
        CONCAT(MONTHNAME(date), ' ', YEAR(date)) AS month_year, 
        SUM(amount) AS total 
    FROM invoice 
    GROUP BY YEAR(date), MONTH(date)
    ORDER BY YEAR(date), MONTH(date)";

$invoicesResult = $conn->query($invoicesQuery);
$invoicesData = [];
while ($row = $invoicesResult->fetch_assoc()) {
    $invoicesData[$row['month_year']] = $row['total'];
}

echo json_encode([
    'appointments' => $appointmentsData,
    'invoices' => $invoicesData,
]);

$conn->close();
?>