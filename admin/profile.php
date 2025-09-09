<?php
session_start();
include '../middleware/adminmiddleware.php';
include 'includes/header.php';
include '../config/dbconnection.php';
include 'includes/alert.php';

$cus_id = $_SESSION['auth_user']['userId'];

$search_query = "SELECT * FROM officer WHERE officer_id = ?";
$stmt = $conn->prepare($search_query);
$stmt->bind_param("i", $cus_id);
$stmt->execute();
$result = $stmt->get_result();
$user = mysqli_fetch_assoc($result);

if ($result->num_rows > 0) {
  $officer = $result->fetch_assoc();
} else {
  // Handle case where no record is found
  $officer = [
    'fname' => '',
    'lname' => '',
    'email' => ''
  ];
}
?>

<body>
  <link rel="stylesheet" href="/AutoMobile Project/admin/assets/css/meeting.css">
  <link rel="stylesheet" href="/AutoMobile Project/admin/assets/css/admin-profile.css">

  <div class="dashboard-wrapper">
    <?php include 'includes/sideMenu.php'; ?>
    <div class="content">
      <?php include 'includes/navbar.php'; ?>

      <main>
        <div class="header">
          <h1>Update Profile</h1>
        </div>

        <div class="profile-container">
          <h1>PROFILE SETTINGS</h1>
          <hr class="divider">
          <form action="functions/editProfile.php" method="POST" class="profile-form" enctype="multipart/form-data">
            <div class="profile-picture-section">
              <div class="wrapper" id="wrapper" style="background: url('<?php echo $_SESSION['auth_user']['profile_pic']; ?>');  background-size: cover;">
                <input type="file" class="my_file" id="fileInput" name="profile_pic" accept="image/*">
              </div>
              <p>Change Profile Picture</p>
            </div>
            <div class="form-group">
              <div class="left-half">
                <label for="first-name">First Name</label>
                <input type="text" id="first-name" name="first_name" value="<?php echo htmlspecialchars($user['fname']); ?>" placeholder="First Name">
              </div>
              <div class="right-half">
                <label for="last-name">Last Name</label>
                <input type="text" id="last-name" name="last_name" value="<?php echo htmlspecialchars($user['lname']); ?>" placeholder="Last Name">
              </div>
            </div><br>

            <div class="form-group">
              <div class="left-half">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" placeholder="example@gmail.com">
              </div>

              <div class="right-half">
                <label for="phone">Phone No</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" placeholder="Phone No">
              </div>
            </div><br>

            <h2>Address</h2><br>

            <div class="form-group">
              <div class="left-half">
                <label for="no">No.</label>
                <input type="text" id="no" name="address_no" value="<?php echo htmlspecialchars($user['address_no']); ?>" placeholder="Address Number">
              </div>
              <div class="right-half">
                <label for="street-name">Street</label>
                <input type="text" id="street-name" name="street_name" value="<?php echo htmlspecialchars($user['street']); ?>" placeholder="Street Name">
              </div>
            </div><br>

            <div class="form-group">
              <div class="left-half">
                <label for="city">City</label>
                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($user['city']); ?>" placeholder="City">
              </div>
              <div class="right-half">
                <label for="district">District</label>
                <input type="text" id="district" name="district" value="<?php echo htmlspecialchars($user['district']); ?>" placeholder="District">
              </div>
            </div><br>

            <div class="form-group">
              <div class="left-half">
                <label for="zip-code">Zip Code</label>
                <input type="text" id="zip-code" name="zip_code" value="<?php echo htmlspecialchars($user['zip_code']); ?>" placeholder="Zip Code">
              </div>
            </div><br>

            <div class="form-group">
              <div class="left-half">
                <div class="password-container">
                  <label for="password">Password</label>
                  <input type="password" id="password" name="password" placeholder="Enter Password">
                  <button type="button" class="toggle-password"><i class="fas fa-eye"></i></button>
                </div>
              </div>
              <div class="right-half">
                <div class="password-container">
                  <label for="re-password">Confirm Password</label>
                  <input type="password" id="re-password" name="re-password" placeholder="Re-Enter Password">
                  <button type="button" class="toggle-password2"><i class="fas fa-eye"></i></button>
                </div>
              </div>
            </div><br>

            <!--<div class="form-group-center password-container">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" placeholder="Enter Password">
              <button type="button" class="toggle-password"><i class="fas fa-eye"></i></button>
            </div>-->
            <button type="submit" class="edit-btn" name="updateBtn">Update</button>
          </form>
        </div>
      </main>
    </div>
  </div>

  <?php include 'includes/footer.php'; ?>
  <script src="/AutoMobile Project/admin/assets/js/admin-profile.js"></script>
  <script src="/AutoMobile Project/admin/assets/js/index.js"></script>
</body>

</html>