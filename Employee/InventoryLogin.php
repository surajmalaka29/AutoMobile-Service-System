<?php
include '../config/dbconnection.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $login_query = "SELECT * FROM officer WHERE email='$username'";
    $login_query_run = mysqli_query($conn, $login_query);

    if ($login_query_run && mysqli_num_rows($login_query_run) > 0) {
        $userdata = mysqli_fetch_array($login_query_run);
        $hashed_password = $userdata['password'];

        if (password_verify($password, $hashed_password)) {
            session_start();
            $_SESSION['message'] = "Logged In Successfully";
            header('Location: addItem.php');
            exit();
        } else {
            $_SESSION['message'] = "Invalid username or password";
        }
    } else {
        $_SESSION['message'] = "Invalid username or password";
    }
}
?>

<?php
include 'includes/header.php';
include 'includes/navbar.php';
?>

<link rel="stylesheet" href="assets/css/customers.css">

<div class="navcontainer">
    <div class="item back">
        <h2 class="clickable" onclick="redirectTo('home.php')">
            <i class="fa-solid fa-chevron-left fa-lg" style="color: #952B1A;"></i>
            Back
        </h2>
    </div>
    <div class="item home">
        <h2 class="clickable" onclick="redirectTo('home.php')">Home</h2>
    </div>
    <div class="item name">
        <h1>Inventory System</h1>
        <hr>
    </div>
</div>

<div class="search-box">
    <form action="InventoryLogin.php" method="POST">
        <i class="fa-solid fa-key fa-10x" style="color: #952b1a;"></i>
        <input type="text" name="username" placeholder="@username">
        <input type="password" name="password" placeholder="Password">
        <button type="submit" name="login" class="Search">Login</button>
    </form>
</div>

<script src="/AutoMobile Project/Employee/assets/js/script.js"></script>
<script src="/AutoMobile Project/Employee/assets/js/inventory.js"></script>
</body>

</html>