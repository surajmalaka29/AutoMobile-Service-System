<?php
session_start();
include('../../config/dbconnection.php');

if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    // Update the appointment table status to 'Completed' using MySQLi
    $stmt = $conn->prepare("UPDATE appointment SET status = 'Cancelled' WHERE app_id = ?");
    $stmt->bind_param("i", $booking_id);
    

    if ($stmt->execute()) {
        $_SESSION['message'] = "booking canceled Successfully";
        header("location: ../bookings.php");
    } else {
        $_SESSION['message'] = "Database error";
        header("location: ../bookings.php");
    }
}