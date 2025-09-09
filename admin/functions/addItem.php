<?php
session_start();
include('../../config/dbconnection.php');

// Add Vehicle Functionality
if (isset($_POST['addItem'])) {

    $itemName = mysqli_real_escape_string($conn, $_POST['itemName']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $buyingPrice = mysqli_real_escape_string($conn, $_POST['buyingPrice']);
    $sellingPrice = mysqli_real_escape_string($conn, $_POST['sellingPrice']);

    $insert_query = "INSERT INTO inventory (item_name, category, brand, quantity, unit_buying_price, unit_price)
                    VALUES ('$itemName', '$category', '$brand', '$quantity', '$buyingPrice', '$sellingPrice')";
    $insert_query_run = mysqli_query($conn, $insert_query);

    if ($insert_query_run) {
        $_SESSION['message'] = "Item Added Successfully";
        header("location: ../inventory.php");
    } else {
        $_SESSION['message'] = "Database error";
        header("location: ../inventory.php");
    }
}

// Edit Vehicle Functionality
if (isset($_POST['editItem'])) {

    $itemId = mysqli_real_escape_string($conn, $_POST['itemId']);
    $itemName = mysqli_real_escape_string($conn, $_POST['itemName']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $buyingPrice = mysqli_real_escape_string($conn, $_POST['buyingPrice']);
    $sellingPrice = mysqli_real_escape_string($conn, $_POST['sellingPrice']);

    $update_query = "UPDATE inventory 
    SET item_name = '$itemName', 
        category = '$category', 
        brand = '$brand', 
        quantity = '$quantity', 
        unit_buying_price = '$buyingPrice', 
        unit_price = '$sellingPrice' 
    WHERE item_id = '$itemId'";
    $update_query_run = mysqli_query($conn, $update_query);

    if ($update_query_run) {
        $_SESSION['message'] = "Item Updated Successfully";
        header("location: ../inventory.php");
    } else {
        $_SESSION['message'] = "Database error";
        header("location: ../inventory.php");
    }
}


if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];
    $delete_query = "DELETE FROM inventory WHERE item_id = '$item_id'";
    $delete_query_run = mysqli_query($conn, $delete_query);

    if ($delete_query_run) {
        $_SESSION['message'] = "Item Deleted Successfully";
        header("location: ../inventory.php");
    } else {
        $_SESSION['message'] = "Database error";
        header("location: ../inventory.php");
    }

}