<?php
include '../middleware/adminmiddleware.php';
include 'includes/header.php';
include '../config/dbconnection.php';

$cus_id = $_SESSION['auth_user']['userId'];

// Capture the filter values from the GET request
$itemName = isset($_GET['itemName']) ? $_GET['itemName'] : '';
$category = isset($_POST['category']) ? $_POST['category'] : '';

// Get the current page and rows per page from the URL or set default values
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Default to page 1
//$rowsPerPage = isset($_GET['rowsPerPage']) ? (int)$_GET['rowsPerPage'] : 8; // Default to 10 rows per page
$rowsPerPage = 8; // Fixed rows per page

// Calculate the offset for the SQL query
$offset = ($page - 1) * $rowsPerPage;

// Base SQL query
$sql = "SELECT item_id, item_name, category, quantity, unit_price
          FROM inventory";

// Initialize an array to store filter parameters
$params = [];
$types = '';

// Initialize an array to hold WHERE conditions
$whereConditions = [];

// Add filters to the query if values are selected
if ($itemName) {
  $whereConditions[] = "item_name = ?";
  $params[] = $itemName;
  $types .= 's'; // 's' for string
}
if ($category) {
  $whereConditions[] = "category = ?";
  $params[] = $category;
  $types .= 's'; // 's' for string
}

// If there are any conditions, append them to the SQL query
if (count($whereConditions) > 0) {
  $sql .= " WHERE " . implode(" AND ", $whereConditions);
}

// Add ORDER BY for sorting
$sql .= " ORDER BY item_id ASC"; // Use DESC for descending order

// Add LIMIT and OFFSET for pagination (these should always be at the end of the query)
$sql .= " LIMIT ? OFFSET ?";
$params[] = $rowsPerPage;
$params[] = $offset;
$types .= 'ii'; // 'ii' for integer

// Prepare and execute the query
$stmt = $conn->prepare($sql);

// Bind the parameters to the statement
if ($types) {
  $stmt->bind_param($types, ...$params);
}

// Execute the query
$stmt->execute();
$query_run = $stmt->get_result();

// category Dropdown
$sql_category = "SELECT DISTINCT category FROM inventory ORDER BY category ASC";
$stmt_category = $conn->prepare($sql_category);
$stmt_category->execute();
$category_result = $stmt_category->get_result();
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
            <h1>Inventory Management</h1>
          </div>
        </div>

        <div class="container">
          <div class="toolbar">
            <div class="searchOption">
              <input type="text" id="searchBox" placeholder="Search itemName..." autocomplete="off" />
              <div class="suggestions-box" id="suggestions-box"></div>
            </div>
            <button id="newMeetingBtn">Add New Item</button>
          </div>

          <form method="POST" action="inventory.php">
            <div class="filters">
              <select id="category" name="category">
                <option value="" <?php echo empty($category) ? 'selected' : ''; ?>>All Categories</option>
                <?php
                while ($row = $category_result->fetch_assoc()) {
                  $selected = ($category == $row['category']) ? 'selected' : ''; // Check if the option is selected
                  echo "<option value='{$row['category']}' $selected>{$row['category']}</option>";
                }
                ?>
              </select>
            </div>
          </form>

          <table id="meetingsTable">
            <thead>
              <tr>
                <th>Item ID</th>
                <th>Item Name</th>
                <th>Category</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Check if the query returned any results
              if (mysqli_num_rows($query_run) > 0) {
                // Loop through the results and create table rows
                while ($row = mysqli_fetch_assoc($query_run)) {
                  echo "<tr>";
                  echo "<td>" . sprintf("I%03d", htmlspecialchars($row['item_id'])) . "</td>";
                  echo "<td>" . $row['item_name'] . "</td>";
                  echo "<td>" . $row['category'] . "</td>";
                  echo "<td>" . $row['quantity'] . "</td>";
                  echo "<td>" . $row['unit_price'] . "</td>";
                  echo "<td>";
                  echo "<a class='edit clickable' data-id='" . $row['item_id'] . "'>Edit</a>" . " | ";
                  echo "<a class='delete clickable' data-id='" . $row['item_id'] . "'>Delete</a>";
                  echo "</td>";
                  echo "</tr>";
                }
              } else {
                // If no data found, display a message
                echo "<tr><td colspan='6'>No vehicles found</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
        <?php
        // Get the total number of records
        $sql_count = "SELECT COUNT(*) AS total FROM inventory";
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
  <br><br>

  <!-- Add New Item Modal -->
  <div id="addVehicleModal" class="modal add-vehicle-modal">
    <div class="modal-content">
      <span class="close-button" id="closeAddVehicleModal">&times;</span>
      <h2>Add New Item</h2>
      <form id="addVehicleForm" action="/AutoMobile Project/admin/functions/addItem.php" method="POST">
        <input type="hidden" id="itemId" name="itemId"> <!-- Hidden field for item ID -->
        <div class="form-group">
          <label for="itemName">Item Name:</label>
          <input type="text" id="itemName" name="itemName" required>
        </div>
        <div class="form-group">
          <label for="category">Category:</label>
          <input type="text" id="category" name="category" required>
        </div>
        <div class="form-group">
          <label for="brand">Brand:</label>
          <input type="text" id="brand" name="brand" required>
        </div>
        <div class="form-group">
          <label for="quantity">Quantity:</label>
          <input type="text" id="quantity" name="quantity" required>
        </div>
        <div class="form-group">
          <label for="buyingPrice">Buying Price:</label>
          <input type="text" id="buyingPrice" name="buyingPrice" required>
        </div>
        <div class="form-group">
          <label for="sellingPrice">Selling Price:</label>
          <input type="text" id="sellingPrice" name="sellingPrice" required>
        </div>
        <button type="submit" class="addbtn" id="submitAddVehicleForm" name="addItem" style="display: none;">Add Item</button>
        <button type="submit" class="editbtn" id="submitAddVehicleForm" name="editItem" style="display: none;">Update Item</button>
      </form>
    </div>
  </div>

  <!-- Booking Details Modal -->
  <div id="viewCustomerModal1" class="modal customer-details-modal-1">
    <div class="modal-content">
      <span class="close-button" id="closeCustomerModal1">&times;</span>
      <h2>Booking Details</h2>
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
              <td>Item-ID</td>
              <td id="itemId">1</td>
            </tr>
            <tr>
              <td>Item Name</td>
              <td id="itemName">Engine Oil</td>
            </tr>
            <tr>
              <td>Category</td>
              <td id="category">Oil</td>
            </tr>
            <tr>
              <td>Quantity</td>
              <td id="quantity">100</td>
            </tr>
            <tr>
              <td>Price</td>
              <td id="price">Rs.50,000</td>
            </tr>
          </tbody>
        </table>
      </div>
      <button class="close-modal-btn-1" id="closeCustomerDetailsBtn1">Close</button>
    </div>
  </div>

  <?php include 'includes/footer.php'; ?> <!-- Include the footer -->

  <script src="/AutoMobile Project/admin/assets/js/index.js"></script>
  <script src="/AutoMobile Project/admin/assets/js/inventory.js"></script>
</body>

</html>