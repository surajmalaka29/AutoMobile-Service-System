<?php
session_start();
include('../../config/dbconnection.php');

// Add Vehicle Functionality
if (isset($_POST['addVehicle'])) {

    $company = mysqli_real_escape_string($conn, $_POST['vehicleManufacturer']);
    $model = mysqli_real_escape_string($conn, $_POST['vehicleModel']);
    $manufacturedYear = mysqli_real_escape_string($conn, $_POST['vehicleYear']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $licensePlateNo = mysqli_real_escape_string($conn, $_POST['vehicleRegistration']);
    $engineNo = mysqli_real_escape_string($conn, $_POST['engineNo']);
    $chassisNo = mysqli_real_escape_string($conn, $_POST['chasissNo']);
    $userId = mysqli_real_escape_string($conn, $_POST['vehicleCustomerName']);

    $insert_query = "INSERT INTO vehicle (company, model, year, category, license_no, engine_no, chasis_no, cus_id)
                        VALUES ('$company', '$model', '$manufacturedYear', '$category', '$licensePlateNo', '$engineNo', '$chassisNo', '$userId')";
    $insert_quert_run = mysqli_query($conn, $insert_query);

    if ($insert_quert_run) {
        $_SESSION['message'] = "Vehicle Added Successfully";
        header("location: ../vehicles.php");
    } else {
        $_SESSION['message'] = "Database error";
        header("location: ../vehicles.php");
    }
}

// Edit Vehicle Functionality
if (isset($_POST['editVehicle'])) {

    $vehicleId = mysqli_real_escape_string($conn, $_POST['vehicleId']);
    $company = mysqli_real_escape_string($conn, $_POST['vehicleManufacturer']);
    $model = mysqli_real_escape_string($conn, $_POST['vehicleModel']);
    $manufacturedYear = mysqli_real_escape_string($conn, $_POST['vehicleYear']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $licensePlateNo = mysqli_real_escape_string($conn, $_POST['vehicleRegistration']);
    $engineNo = mysqli_real_escape_string($conn, $_POST['engineNo']);
    $chassisNo = mysqli_real_escape_string($conn, $_POST['chasissNo']);
    $userId = mysqli_real_escape_string($conn, $_POST['vehicleCustomerName']);

    $update_query = "UPDATE vehicle 
    SET cus_id = '$userId', 
        license_no = '$licensePlateNo', 
        company = '$company', 
        model = '$model', 
        year = '$manufacturedYear', 
        chasis_no = '$chassisNo',
        engine_no = '$engineNo',
        category = '$category' 
    WHERE vehicle_id = '$vehicleId'";
    $update_query_run = mysqli_query($conn, $update_query);

    if ($update_query_run) {
        $_SESSION['message'] = "Vehicle Updated Successfully";
        header("location: ../vehicles.php");
    } else {
        $_SESSION['message'] = "Database error";
        header("location: ../vehicles.php");
    }
}


if (isset($_GET['vehicle_id'])) {
    $vehicle_id = $_GET['vehicle_id'];
    $delete_query = "DELETE FROM vehicle WHERE vehicle_id = '$vehicle_id'";
    $delete_query_run = mysqli_query($conn, $delete_query);

    if ($delete_query_run) {
        $_SESSION['message'] = "Vehicle Deleted Successfully";
        header("location: ../vehicles.php");
    } else {
        $_SESSION['message'] = "Database error";
        header("location: ../vehicles.php");
    }
}