<?php
include '../middleware/adminmiddleware.php';
include 'includes/header.php';
include '../config/dbconnection.php';

$cus_id = $_SESSION['auth_user']['userId'];

// Capture the filter values from the GET request
$manufacturer = isset($_POST['manufacturer']) ? $_POST['manufacturer'] : '';
$model = isset($_POST['model']) ? $_POST['model'] : '';
$category = isset($_POST['category']) ? $_POST['category'] : '';
$year = isset($_POST['year']) ? $_POST['year'] : '';
$customer = isset($_GET['customer']) ? $_GET['customer'] : '';

$sql = "SELECT 
            c.cus_Id AS customer_id, 
            CONCAT(c.fname, ' ', c.lname) AS customer_name,
            c.profile_pic,
            a.app_date,
            a.app_time,
            IFNULL(CONCAT(o.fname, ' ', o.lname), 'Unassigned') AS mechanic_name, 
            a.status
          FROM 
            appointment a
          JOIN 
            vehicle v ON a.vehicle_id = v.vehicle_id
          JOIN 
            customer c ON v.cus_id = c.cus_id
          LEFT JOIN 
            officer o ON a.officer_id = o.officer_id
          ORDER By 
            a.app_date DESC, a.app_time DESC";

// Prepare and execute the query
$stmt = $conn->prepare($sql);
$stmt->execute();
$query_run = $stmt->get_result();
?>

<body>
  <!-- Wrapper for sidebar and main content -->
  <div class="dashboard-wrapper">
    <!-- import sideMenu.php -->
    <?php include 'includes/sideMenu.php'; ?>

    <!-- Main Content -->
    <div class="content">
      <!-- Navbar -->
      <?php include 'includes/navbar.php'; ?>

      <main>
      <?php include 'includes/alert.php'; ?>
        <div class="header">
          <div class="left">
            <h1>Dashboard</h1>
          </div>
          <a href="/AutoMobile Project/functions/report.php" class="report">
            <i class='bx bx-cloud-download'></i>
            <span >Download Report</span>
          </a>
        </div>

        <!-- Insights -->
        <ul class="insights">
          <li>
            <i class='bx bx-car'></i>
            <span class="info">
              <?php
              // SQL query to count the number of rows
              $sql = "SELECT COUNT(*) AS totalvehicles FROM vehicle";
              $stmt = $conn->prepare($sql);
              $stmt->execute();
              $result = $stmt->get_result();
              $row = $result->fetch_assoc();

              // Output the total number of rows
              echo "<h3>" . $row['totalvehicles'] . "</h3>";
              ?>
              <p>Total Vehicles</p>
            </span>
          </li>
          <li>
            <i class='bx bx-male'></i>
            <span class="info">
              <?php
              // SQL query to count the number of rows
              $sql = "SELECT COUNT(*) AS totalcustomers FROM customer";
              $stmt = $conn->prepare($sql);
              $stmt->execute();
              $result = $stmt->get_result();
              $row = $result->fetch_assoc();

              // Output the total number of rows
              echo "<h3>" . $row['totalcustomers'] . "</h3>";
              ?>
              <p>Total Customers</p>
            </span>
          </li>
          <li>
            <i class='bx bx-line-chart'></i>
            <span class="info">
              <?php
              // SQL query to count the number of rows
              $sql = "SELECT COUNT(*) AS totalservices FROM service";
              $stmt = $conn->prepare($sql);
              $stmt->execute();
              $result = $stmt->get_result();
              $row = $result->fetch_assoc();

              // Output the total number of rows
              echo "<h3>" . $row['totalservices'] . "</h3>";
              ?>
              <p>Services</p>
            </span>
          </li>
          <li>
            <i class='bx bx-money'></i>
            <span class="info">
              <?php
              // SQL query to count the number of rows
              $sql = "SELECT FLOOR(SUM(amount)) AS totalamount FROM invoice";
              $stmt = $conn->prepare($sql);
              $stmt->execute();
              $result = $stmt->get_result();
              $row = $result->fetch_assoc();

              // Output the total number of rows
              echo "<h3>Rs. " . $row['totalamount'] . "</h3>";
              ?>
              <p>Total Sales</p>
            </span>
          </li>
        </ul>

        <!-- Charts -->
        <div class="graphBox">
          <div class="box">
            <canvas id="myChart"></canvas>
          </div>
          <div class="box">
            <canvas id="earning"></canvas>
          </div>
        </div>

        <!-- Appointments -->
        <div class="bottom-data">
          <div class="orders">
            <div class="header">
              <i class='bx bx-receipt'></i>
              <h3>Appointments</h3>
              <a href="#" class="report">
                <i class='bx bx-plus-circle'></i>
                <span>Place Appointment</span>
              </a>
              <i class='bx bx-filter'></i>
              <i class='bx bx-search'></i>
            </div>
            <table>
              <thead>
                <tr>
                  <th>Customer ID</th>
                  <th>Customer Name</th>
                  <th>Appointment Date</th>
                  <th>Appointment Time</th>
                  <th>Mechanic</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                // Check if the query returned any results
                if ($query_run->num_rows > 0) {
                  // Loop through the results and create table rows
                  while ($row = $query_run->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . sprintf("#%03d", htmlspecialchars($row['customer_id'])) . "</td>";
                    echo "<td>
                              <div class='customer-info'>
                                <img src='/AutoMobile Project/User/assets/img/profile_pics/" .
                                    (!empty($row['profile_pic']) ? $row['profile_pic'] : 'default.jpg') . "' 
                                     alt='Profile Picture' 
                                     style='width:40px; height:40px; border-radius:50%; margin-right:10px;'>
                                <p>" . htmlspecialchars($row['customer_name']) . "</p>
                              </div>
                            </td>";
                    echo "<td>" . date("d-m-Y", strtotime($row['app_date'])) . "</td>";
                    echo "<td>" . date("H:i", strtotime($row['app_time'])) . "</td>";
                    echo "<td>" . htmlspecialchars($row['mechanic_name']) . "</td>";
                    if ($row['status'] == 'Completed') {
                      echo "<td><span class='status completed'>" . "Completed" . "</span></td>";
                    } else if ($row['status'] == 'Pending') {
                      echo "<td><span class='status pending'>" . "Pending" . "</span></td>";
                    } else if ($row['status'] == 'Confirmed') {
                      echo "<td><span class='status ongoing'>" . "On-Going" . "</span></td>";
                    } else {
                      echo "<td><span class='status process'>" . "Cancelled" . "</span></td>";
                    }
                    echo "</tr>";
                  }
                } else {
                  // If no data found, display a message
                  echo "<tr><td colspan='5'>No appointments found</td></tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
        <!-- End of Orders -->
      </main>
    </div>
  </div>
  <!-- End of Wrapper -->


  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
  <script src="/AutoMobile Project/admin/assets/js/index.js"></script>
  <script src="/AutoMobile Project/admin/assets/js/chart.js"></script>
</body>

</html>