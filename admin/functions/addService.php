<?php
session_start();
include('../../config/dbconnection.php');

// Add Vehicle Functionality
if (isset($_POST['addService'])) {

    $serviceName = mysqli_real_escape_string($conn, $_POST['serviceName']);
    $hours = mysqli_real_escape_string($conn, $_POST['hours']);
    $serviceCharge = mysqli_real_escape_string($conn, $_POST['serviceCharge']);


    $insert_query = "INSERT INTO service (service_type, hours, service_charge)
                        VALUES ('$serviceName', '$hours', '$serviceCharge')";
    $insert_quert_run = mysqli_query($conn, $insert_query);

    if ($insert_quert_run) {
        $_SESSION['message'] = "Service Added Successfully";
        header("location: ../service.php");
    } else {
        $_SESSION['message'] = "Database error";
        header("location: ../service.php");
    }
}

// Edit Vehicle Functionality
if (isset($_POST['editService'])) {

    $serviceId = mysqli_real_escape_string($conn, $_POST['serviceId']);
    $serviceName = mysqli_real_escape_string($conn, $_POST['serviceName']);
    $hours = mysqli_real_escape_string($conn, $_POST['hours']);
    $serviceCharge = mysqli_real_escape_string($conn, $_POST['serviceCharge']);

    $update_query = "UPDATE service SET service_type = '$serviceName', hours = '$hours', service_charge = '$serviceCharge' WHERE service_id = '$serviceId'"; 
    $update_query_run = mysqli_query($conn, $update_query);

    if ($update_query_run) {
        $_SESSION['message'] = "Service Updated Successfully";
        header("location: ../service.php");
    } else {
        $_SESSION['message'] = "Database error";
        header("location: ../service.php");
    }
}


if (isset($_GET['service_id'])) {
    $service_id = $_GET['service_id'];
    $delete_query = "DELETE FROM service WHERE service_id = '$service_id'";
    $delete_query_run = mysqli_query($conn, $delete_query);

    if ($delete_query_run) {
        $_SESSION['message'] = "Service Deleted Successfully";
        header("location: ../service.php");
    } else {
        $_SESSION['message'] = "Database error";
        header("location: ../service.php");
    }
}