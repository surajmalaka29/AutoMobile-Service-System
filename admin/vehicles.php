<?php
include '../middleware/adminmiddleware.php';
include 'includes/header.php';
include '../config/dbconnection.php';

$cus_id = $_SESSION['auth_user']['userId'];

// Capture the filter values from the GET request
$customer = isset($_GET['customer']) ? $_GET['customer'] : '';
$manufacturer = isset($_POST['manufacturer']) ? $_POST['manufacturer'] : '';
$model = isset($_POST['model']) ? $_POST['model'] : '';
$category = isset($_POST['category']) ? $_POST['category'] : '';
$year = isset($_POST['year']) ? $_POST['year'] : '';

// Get the current page and rows per page from the URL or set default values
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Default to page 1
//$rowsPerPage = isset($_GET['rowsPerPage']) ? (int)$_GET['rowsPerPage'] : 8; // Default to 10 rows per page
$rowsPerPage = 8; // Fixed rows per page

// Calculate the offset for the SQL query
$offset = ($page - 1) * $rowsPerPage;

// Base SQL query
$sql = "SELECT c.fname, c.lname, v.vehicle_id, v.category, v.model, v.company, v.license_no, v.year 
          FROM vehicle v, customer c 
          WHERE v.cus_id = c.cus_id";

// Add filters to the query if values are selected
if ($customer) {
  $sql .= " AND CONCAT(c.fname, ' ', c.lname) = ?";
}
if ($manufacturer) {
  $sql .= " AND v.company = ?";
}
if ($model) {
  $sql .= " AND v.model = ?";
}
if ($category) {
  $sql .= " AND v.category = ?";
}
if ($year) {
  $sql .= " AND v.year = ?";
}

// Add ORDER BY for sorting
$sql .= " ORDER BY vehicle_id ASC"; // Use DESC for descending order

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
if ($manufacturer) {
  $params[] = $manufacturer;
  $types .= 's'; // 's' for string
}
if ($model) {
  $params[] = $model;
  $types .= 's'; // 's' for string
}
if ($category) {
  $params[] = $category;
  $types .= 's'; // 's' for string
}
if ($year) {
  $params[] = $year;
  $types .= 'i'; // 'i' for integer
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

// Manufacturer Dropdown
$sql_manufacturer = "SELECT DISTINCT company FROM vehicle ORDER BY company ASC";
$stmt_manufacturer = $conn->prepare($sql_manufacturer);
$stmt_manufacturer->execute();
$manufacturer_result = $stmt_manufacturer->get_result();

// Model Dropdown
$sql_model = "SELECT DISTINCT model FROM vehicle ORDER BY model ASC";
$stmt_model = $conn->prepare($sql_model);
$stmt_model->execute();
$model_result = $stmt_model->get_result();

// Category Dropdown
$sql_category = "SELECT DISTINCT category FROM vehicle ORDER BY category ASC";
$stmt_category = $conn->prepare($sql_category);
$stmt_category->execute();
$category_result = $stmt_category->get_result();

// Year Dropdown
$sql_year = "SELECT DISTINCT year FROM vehicle ORDER BY year ASC";
$stmt_year = $conn->prepare($sql_year);
$stmt_year->execute();
$year_result = $stmt_year->get_result();

?>

<body>
  <link rel="stylesheet" href="/AutoMobile Project/admin/assets/css/meeting.css">
  <link rel="stylesheet" href="/AutoMobile Project/admin/assets/css/vehicle.css">

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
            <h1>Vehicles</h1>
          </div>
        </div>

        <div class="container">
          <div class="toolbar">
            <div class="searchOption">
              <input type="text" id="searchBox" placeholder="Search Vehicle..." autocomplete="off" />
              <div class="suggestions-box" id="suggestions-box"></div>
            </div>
            <button id="newMeetingBtn">+ New Vehicle</button>
          </div>

          <form method="POST" action="vehicles.php">
            <div class="filters">
              <select id="manufacturer" name="manufacturer">
                <option value="" <?php echo empty($manufacturer) ? 'selected' : ''; ?>>All Manufacturers</option>
                <?php
                while ($row = $manufacturer_result->fetch_assoc()) {
                  $selected = ($manufacturer == $row['company']) ? 'selected' : ''; // Check if the option is selected
                  echo "<option value='{$row['company']}' $selected>{$row['company']}</option>";
                }
                ?>
              </select>

              <select id="model" name="model">
                <option value="" <?php echo empty($model) ? 'selected' : ''; ?>>All Models</option>
                <?php
                while ($row = $model_result->fetch_assoc()) {
                  $selected = ($model == $row['model']) ? 'selected' : ''; // Check if the option is selected
                  echo "<option value='{$row['model']}' $selected>{$row['model']}</option>";
                }
                ?>
              </select>

              <select id="category" name="category">
                <option value="" <?php echo empty($fuelType) ? 'selected' : ''; ?>>All Vehicle Types</option>
                <?php
                while ($row = $category_result->fetch_assoc()) {
                  $selected = ($category == $row['category']) ? 'selected' : ''; // Check if the option is selected
                  echo "<option value='{$row['category']}' $selected>{$row['category']}</option>";
                }
                ?>
              </select>

              <select id="year" name="year">
                <option value="" <?php echo empty($year) ? 'selected' : ''; ?>>All Years</option>
                <?php
                while ($row = $year_result->fetch_assoc()) {
                  $selected = ($year == $row['year']) ? 'selected' : ''; // Check if the option is selected
                  echo "<option value='{$row['year']}' $selected>{$row['year']}</option>";
                }
                ?>
              </select>
            </div>
          </form>

          <table id="meetingsTable">
            <thead>
              <tr>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Vehicle Registration</th>
                <th>Year</th>
                <th>Manufacturer</th>
                <th>Model Name</th>
                <th>Vehicle Type</th>
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
                  echo "<td>" . sprintf("V%03d", htmlspecialchars($row['vehicle_id'])) . "</td>";
                  echo "<td>" . $row['fname'] . " " . $row['lname'] . "</td>";
                  echo "<td>" . $row['license_no'] . "</td>";
                  echo "<td>" . $row['year'] . "</td>";
                  echo "<td>" . $row['company'] . "</td>";
                  echo "<td>" . $row['model'] . "</td>";
                  echo "<td>" . $row['category'] . "</td>";
                  echo "<td>";
                  echo "<a class='edit clickable' data-id='" . $row['vehicle_id'] . "'>Edit</a>" . " | ";
                  echo "<a class='delete clickable' data-id='" . $row['vehicle_id'] . "'>Delete</a>";
                  echo "</td>";
                  echo "</tr>";
                }
              } else {
                // If no data found, display a message
                echo "<tr><td colspan='8'>No vehicles found</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
        <?php
        // Get the total number of records
        $sql_count = "SELECT COUNT(*) AS total FROM vehicle v, customer c WHERE v.cus_id = c.cus_id";
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
        <!--<div class="pagination">
          <span>Showing 1 of 1 Results</span>
          <select id="rowsPerPage">
            <option value="100">100</option>
          </select>
          <div class="pagination-controls">
            <button>&laquo;</button>
            <button>1</button>
            <button>&raquo;</button>
          </div>
        </div>-->
    </div>
    </main>
  </div><br><br>

  <!-- Add New Vehicle Modal -->
  <div id="addVehicleModal" class="modal add-vehicle-modal">
    <div class="modal-content">
      <span class="close-button" id="closeAddVehicleModal">&times;</span>
      <h2>Add New Vehicle</h2>
      <form id="addVehicleForm" action="/AutoMobile Project/admin/functions/addVehicle.php" method="POST">
        <input type="hidden" id="vehicleId" name="vehicleId" />
        <div class="form-group">
          <label for="vehicleCustomerName">Customer ID:</label>
          <input type="text" id="vehicleCustomerName" name="vehicleCustomerName" required>
        </div>
        <div class="form-group">
          <label for="vehicleRegistration">Vehicle Registration:</label>
          <input type="text" id="vehicleRegistration" name="vehicleRegistration" required>
        </div>
        <div class="form-group">
          <label for="vehicleManufacturer">Manufacturer:</label>
          <input type="text" id="vehicleManufacturer" name="vehicleManufacturer" required>
        </div>
        <div class="form-group">
          <label for="vehicleModel">Model:</label>
          <input type="text" id="vehicleModel" name="vehicleModel" required>
        </div>
        <div class="form-group">
          <label for="vehicleYear">Year:</label>
          <input type="text" id="vehicleYear" name="vehicleYear" required>
        </div>
        <div class="form-group">
          <label for="chasissNo">Chasiss No:</label>
          <input type="text" id="chasissNo" name="chasissNo" required>
        </div>
        <div class="form-group">
          <label for="engineNo">Engine No:</label>
          <input type="text" id="engineNo" name="engineNo" required>
        </div>
        <div class="form-group">
          <label for="category">Category:</label>
          <select type="text" id="category" name="category" required>
            <option value="" disabled selected>Select a Category</option>
            <option value="Bike">Bike</option>
            <option value="Car">Car</option>
            <option value="Van">Van</option>
            <option value="Truck">Truck</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <button type="submit" class="addbtn" id="submitAddVehicleForm" name="addVehicle">Add Vehicle</button>
        <button type="submit" class="editbtn" id="submitAddVehicleForm" name="editVehicle">Update Vehicle</button>
      </form>
    </div>
  </div>

  <!-- Customer Details Modal -->
  <div id="viewCustomerModal1" class="modal customer-details-modal-1">
    <div class="modal-content">
      <span class="close-button" id="closeCustomerModal1">&times;</span>
      <h2>Customer Details</h2>
      <div class="customer-table-container-1">
        <table class="customer-details-table-1" id="meetingsTable1">
          <thead>
            <tr>
              <th>Detail</th>
              <th>Information</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Customer Name</td>
              <td id="customerName1">Nipuna Lakruwan</td>
            </tr>
            <tr>
              <td>Email</td>
              <td id="customerEmail1">nipuna@example.com</td>
            </tr>
            <tr>
              <td>Phone Number</td>
              <td id="customerPhone1">+94 712345678</td>
            </tr>
            <tr>
              <td>Address</td>
              <td id="customerAddress1">123 Main Street, Colombo</td>
            </tr>
            <tr>
              <td>Registered Vehicles</td>
              <td id="customerVehicles1">
                <ul>
                  <li>WP CA-1234 - Honda</li>
                  <li>WP CA-5678 - Toyota</li>
                </ul>
              </td>
            </tr>
            <tr>
              <td>Registration Date</td>
              <td id="customerRegistrationDate1">2023-05-15</td>
            </tr>
          </tbody>
        </table>
      </div>
      <button class="close-modal-btn-1" id="closeCustomerDetailsBtn1">Close</button>
    </div>
  </div>

  <?php include 'includes/footer.php'; ?> <!-- Include the footer -->

  <script src="/AutoMobile Project/admin/assets/js/index.js"></script>
  <script src="/AutoMobile Project/admin/assets/js/vehicle.js"></script>
</body>

</html>