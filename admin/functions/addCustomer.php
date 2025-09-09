<?php
session_start();
include('../../config/dbconnection.php');

// Add Vehicle Functionality
if (isset($_POST['addCustomer'])) {

    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $membership = mysqli_real_escape_string($conn, $_POST['membership']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $password = password_hash($password, PASSWORD_DEFAULT);

    $insert_query = "INSERT INTO customer (fname, lname, email, phone, membership, password)
                    VALUES ('$firstName', '$lastName', '$email', '$phone', '$membership', '$password')";
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

        
        $_SESSION['message'] = "Customer Added Successfully";
        header("location: ../customer.php");
    } else {
        $_SESSION['message'] = "Database error";
        header("location: ../customer.php");
    }
}

// Edit Vehicle Functionality
if (isset($_POST['editCustomer'])) {

    $customerId = mysqli_real_escape_string($conn, $_POST['customerId']);
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $membership = mysqli_real_escape_string($conn, $_POST['membership']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $password = password_hash($password, PASSWORD_DEFAULT);

    $update_query = "UPDATE customer 
    SET fname = '$firstName', 
        lname = '$lastName', 
        email = '$email', 
        phone = '$phone', 
        membership = '$membership',
        password = '$password'
    WHERE cus_Id = '$customerId'";
    $update_query_run = mysqli_query($conn, $update_query);

    if ($update_query_run) {
        $_SESSION['message'] = "Customer Updated Successfully";
        header("location: ../customer.php");
    } else {
        $_SESSION['message'] = "Database error";
        header("location: ../customer.php");
    }
}


if (isset($_GET['customer_id'])) {
    $customer_id = $_GET['customer_id'];
    $delete_query = "DELETE FROM customer WHERE cus_Id = '$customer_id'";
    $delete_query_run = mysqli_query($conn, $delete_query);

    if ($delete_query_run) {
        $_SESSION['message'] = "Customer Deleted Successfully";
        header("location: ../customer.php");
    } else {
        $_SESSION['message'] = "Database error";
        header("location: ../customer.php");
    }

}