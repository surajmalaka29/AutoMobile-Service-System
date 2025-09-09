<?php
session_start();
include('../../config/dbconnection.php');

// Add Vehicle Functionality
if (isset($_POST['addOfficer'])) {

    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $type = mysqli_real_escape_string($conn, $_POST['Type']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $password = password_hash($password, PASSWORD_DEFAULT);

    $insert_query = "INSERT INTO officer (fname, lname, type, email, phone, role, position, password)
                    VALUES ('$firstName', '$lastName', '$type', '$email', '$phone', '$role', '$position', '$password')";
    $insert_query_run = mysqli_query($conn, $insert_query);

    if ($insert_query_run) {
        // Get the ID of the last inserted officer
        $officer_id = mysqli_insert_id($conn);
        date_default_timezone_set('Asia/Colombo');
        $date = date('Y-m-d H:i:s');
        $status = 0;
        $deleteStatus = 0;
        $message = "Your Account Is Created successfully. Please Reset Your Password";

        // Send a Notification to the customer
        $stmt = $conn->prepare("INSERT INTO notification (date, description, status, delete_status, cus_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiii", $date, $message, $status, $deleteStatus, $officer_id);
        $stmt->execute();

        $_SESSION['message'] = "Officer Added Successfully";
        header("location: ../humanResource.php");
    } else {
        $_SESSION['message'] = "Database error";
        header("location: ../humanResource.php");
    }
}

// Edit Vehicle Functionality
if (isset($_POST['editOfficer'])) {

    $officerId = mysqli_real_escape_string($conn, $_POST['OfficerId']);
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $type = mysqli_real_escape_string($conn, $_POST['Type']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $password = password_hash($password, PASSWORD_DEFAULT);

    $update_query = "UPDATE officer 
    SET fname = '$firstName', 
        lname = '$lastName', 
        type = '$type', 
        email = '$email', 
        phone = '$phone', 
        role = '$role',
        position = '$position',
        password = '$password'
    WHERE officer_id = '$officerId'";
    $update_query_run = mysqli_query($conn, $update_query);

    if ($update_query_run) {
        $_SESSION['message'] = "Officer Updated Successfully";
        header("location: ../humanResource.php");
    } else {
        $_SESSION['message'] = "Database error";
        header("location: ../humanResource.php");
    }
}


if (isset($_GET['officer_id'])) {
    $officer_id = $_GET['officer_id'];
    $delete_query = "DELETE FROM officer WHERE officer_id = '$officer_id'";
    $delete_query_run = mysqli_query($conn, $delete_query);

    if ($delete_query_run) {
        $_SESSION['message'] = "Officer Deleted Successfully";
        header("location: ../humanResource.php");
    } else {
        $_SESSION['message'] = "Database error";
        header("location: ../humanResource.php");
    }

}