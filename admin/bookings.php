<?php
include '../middleware/adminmiddleware.php';
include 'includes/header.php';
include '../config/dbconnection.php';

$cus_id = $_SESSION['auth_user']['userId'];

// Capture the filter values from the GET request
$startDate = isset($_POST['startDate']) ? $_POST['startDate'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';
$endDate = isset($_POST['endDate']) ? $_POST['endDate'] : '';
$customer = isset($_GET['customer']) ? $_GET['customer'] : '';

// Get the current page and rows per page from the URL or set default values
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Default to page 1
//$rowsPerPage = isset($_GET['rowsPerPage']) ? (int)$_GET['rowsPerPage'] : 8; // Default to 10 rows per page
$rowsPerPage = 8; // Fixed rows per page

// Calculate the offset for the SQL query
$offset = ($page - 1) * $rowsPerPage;

// Base SQL query
$sql = "SELECT c.fname, c.lname, v.vehicle_id, v.license_no, a.app_date, a.app_time, a.message, a.app_id, a.status
          FROM appointment a, vehicle v, customer c 
          WHERE a.vehicle_id = v.vehicle_id AND v.cus_id = c.cus_id";

// Add filters to the query if values are selected
if ($customer) {
  $sql .= " AND CONCAT(c.fname, ' ', c.lname) = ?";
}
if ($startDate) {
  $sql .= " AND a.app_date >= ?";
}
if ($endDate) {
  $sql .= " AND a.app_date <= ?";
}
if ($status) {
  $sql .= " AND a.status = ?";
}
$sql .= " ORDER BY a.app_id ASC";
// Add LIMIT and OFFSET for pagination
$sql .= " LIMIT ? OFFSET ?";

// Prepare and execute the query
$stmt = $conn->prepare($sql);

// Bind parameters dynamically based on selected filters
$params = [];
$types = '';
if ($customer) {
  $params[] = $customer;
  $types .= 's';
}
if ($startDate) {
  $params[] = $startDate;
  $types .= 's'; // 's' for string
}
if ($endDate) {
  $params[] = $endDate;
  $types .= 's'; // 's' for string
}
if ($status) {
  $params[] = $status;
  $types .= 's'; // 's' for string
}

$params[] = $rowsPerPage;
$params[] = $offset;
$types .= 'ii'; // 'ii' for integer

// Bind the parameters to the statement
if ($types) {
  $stmt->bind_param($types, ...$params);
}

// Execute the query
$stmt->execute();
$query_run = $stmt->get_result();
?>

<body>
  <link rel="stylesheet" href="/AutoMobile Project/admin/assets/css/booking.css">

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
            <h1>Bookings</h1>
          </div>
        </div>

        <div class="container">
          <div class="toolbar">
            <div class="searchOption">
              <input type="text" id="searchBox" placeholder="Search Customer..." autocomplete="off" />
              <div class="suggestions-box" id="suggestions-box"></div>
            </div>
            <!--<button id="newBookingsBtn">+ New Booking</button>-->
          </div>
          <form method="POST" action="bookings.php">
            <div class="filters">
              <select id="status" name="status">
                <option value="" <?php echo empty($status) ? 'selected' : ''; ?>>All Appointments</option>
                <?php
                $statuses = ['Confirmed', 'Pending', 'Cancelled', 'Completed'];
                foreach ($statuses as $stat) {
                  $selected = ($status == $stat) ? 'selected' : ''; // Check if the option is selected
                  echo "<option value='{$stat}' $selected>{$stat}</option>";
                }
                ?>
              </select>

              <input type="date" id="startDate" name="startDate" value="<?php echo $startDate; ?>" onchange="autoSubmitForm()" />
              <span>-</span>
              <input type="date" id="endDate" name="endDate" value="<?php echo $endDate; ?>" onchange="autoSubmitForm()" />
            </div>
          </form>
          <table id="bookingsTable">
            <thead>
              <tr>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Vehicle Registration</th>
                <th>Booking Date</th>
                <th>Booking Time</th>
                <th>Note</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              mysqli_data_seek($query_run, 0); // Reset the pointer to the first row
              // Check if the query returned any results
              if (mysqli_num_rows($query_run) > 0) {
                // Loop through the results and create table rows
                while ($row = mysqli_fetch_assoc($query_run)) {
                  echo "<tr>";
                  echo "<td>" . $row['app_id'] . "</td>";
                  echo "<td>" . $row['fname'] . " " . $row['lname'] . "</td>";
                  echo "<td>" . $row['license_no'] . "</td>";
                  echo "<td>" . $row['app_date'] . "</td>";
                  echo "<td>" . $row['app_time'] . "</td>";
                  echo "<td>" . $row['message'] . "</td>";
                  echo "<td>" . $row['status'] . "</td>";
                  echo "<td>";
                  if ($row['status'] == 'Pending' || $row['status'] == 'Confirmed') {
                    echo "<a class='cancel clickable' data-id='" . $row['app_id'] . "'>Cancel</a>";
                  } else {
                    echo "<span class='disabled-link'>Cancel</span>";
                  }
                  echo "</td>";
                  echo "</tr>";
                }
              } else {
                // If no data found, display a message
                echo "<tr><td colspan='8'>No Appointments found</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
        <?php
        // Get the total number of records
        $sql_count = "SELECT COUNT(*) AS total FROM appointment";
        $stmt_count = $conn->prepare($sql_count);
        $stmt_count->execute();
        $count_result = $stmt_count->get_result();
        $row_count = $count_result->fetch_assoc();
        $totalRows = $row_count['total'];

        // Calculate the total number of pages
        $totalPages = ceil($totalRows / $rowsPerPage);

        // Display pagination controls
        echo '<div class="pagination">';
        echo '<span>Showing page ' . $page . ' of ' . $totalPages . '</span>';
        echo '<div class="pagination-controls">';
        // Previous button
        // Previous button (disabled on the first page)
        if ($page > 1) {
          echo '<a href="?page=' . ($page - 1)/* . '&rowsPerPage=' . $rowsPerPage*/ . '">&laquo;</a>';
        } else {
          echo '<a href="#" class="disabled">&laquo;</a>'; // Disabled Previous button
        }

        // Active page number
        echo '<a href="#" class="disabled">' . $page . '</a>';

        // Next button (disabled on the last page)
        if ($page < $totalPages) {
          echo '<a href="?page=' . ($page + 1)/* . '&rowsPerPage=' . $rowsPerPage*/ . '">&raquo;</a>';
        } else {
          echo '<a href="#" class="disabled">&raquo;</a>'; // Disabled Next button
        }

        echo '</div>';
        echo '</div>';
        ?>
    </div>
    </main>
  </div>
  </div><br><br>
  <!-- End of Wrapper -->
  <script src="/AutoMobile Project/admin/assets/js/index.js"></script>
  <script src="/AutoMobile Project/admin/assets/js/bookings.js"></script>
</body>
<?php include 'includes/footer.php'; ?> <!-- Include the footer -->

</html>