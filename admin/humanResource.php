<?php
  include '../middleware/adminmiddleware.php';
  include 'includes/header.php';
  include '../config/dbconnection.php';

  $cus_id = $_SESSION['auth_user']['userId'];

  // Capture the filter values from the GET request
  $customer = isset($_GET['customer']) ? $_GET['customer'] : '';
  $role = isset($_POST['role']) ? $_POST['role'] : '';
  $department = isset($_POST['department']) ? $_POST['department'] : '';
  $type = isset($_POST['type']) ? $_POST['type'] : '';

  // Get the current page and rows per page from the URL or set default values
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Default to page 1
  //$rowsPerPage = isset($_GET['rowsPerPage']) ? (int)$_GET['rowsPerPage'] : 8; // Default to 10 rows per page
  $rowsPerPage = 8; // Fixed rows per page
  
  // Calculate the offset for the SQL query
  $offset = ($page - 1) * $rowsPerPage;

  // Base SQL query
  $sql = "SELECT officer_id, fname, lname, officer_id, email, phone, type, role, position FROM officer";

  // Initialize an array to store filter parameters
  $params = [];
  $types = '';

  // Initialize an array to hold WHERE conditions
  $whereConditions = [];

  // Add filters to the query if values are selected
  if ($customer) {
    $whereConditions[] = "CONCAT(fname, ' ', lname) = ?";
    $params[] = $customer;
    $types .= 's'; // 's' for string
  }
  if ($role) {
    $whereConditions[] = "position = ?";
    $params[] = $role;
    $types .= 's'; // 's' for string
  }
  if ($department) {
    $whereConditions[] = "role = ?";
    $params[] = $department;
    $types .= 's'; // 's' for string
  }
  if ($type) {
    $whereConditions[] = "type = ?";
    $params[] = $type;
    $types .= 's'; // 's' for string
  }

  // If there are any conditions, append them to the SQL query
  if (count($whereConditions) > 0) {
    $sql .= " WHERE " . implode(" AND ", $whereConditions);
  }

  // Add ORDER BY for sorting
  $sql .= " ORDER BY officer_id ASC"; // Use DESC for descending order

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

  // Role Dropdown
  $sql_department = "SELECT DISTINCT role FROM officer ORDER BY role ASC";
  $stmt_department = $conn->prepare($sql_department);
  $stmt_department->execute();
  $department_result = $stmt_department->get_result();
  // Role Dropdown
  $sql_role = "SELECT DISTINCT position FROM officer ORDER BY role ASC";
  $stmt_role = $conn->prepare($sql_role);
  $stmt_role->execute();
  $role_result = $stmt_role->get_result();

?>

<body>
  <link rel="stylesheet" href="/AutoMobile Project/admin/assets/css/meeting.css">
  <link rel="stylesheet" href="/AutoMobile Project/admin/assets/css/humanResource.css">

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
        <div class="container">
          <h2>Human Resource</h2>
          <div class="toolbar">
            <div class="searchOption">
              <input type="text" id="searchBox" placeholder="Search Employee..." autocomplete="off" />
              <div class="suggestions-box" id="suggestions-box"></div>
            </div>
            <button id="newMeetingBtn">+ New Employee</button>
          </div>
          <form method="POST" action="humanResource.php">
          <div class="filters">
            <select id="department" name="department">
              <option value="" <?php echo empty($role) ? 'selected' : ''; ?>>All Departments</option>
              <?php
                while ($row = $department_result->fetch_assoc()) {
                  $selected = ($department == $row['role']) ? 'selected' : ''; // Check if the option is selected
                  echo "<option value='{$row['role']}' $selected>{$row['role']}</option>";
                }
              ?>
            </select>
            <select id="type" name="type">
              <option value="" <?php echo empty($type) ? 'selected' : ''; ?>>All Types</option>
              <option value="Full Time" <?php echo $type == 'Full Time' ? 'selected' : ''; ?>>Full Time</option>
              <option value="Part Time" <?php echo $type == 'Part Time' ? 'selected' : ''; ?>>Part Time</option>
            </select>
            <select id="role" name="role">
              <option value="" <?php echo empty($role) ? 'selected' : ''; ?>>All Roles</option>
              <?php
                while ($row = $role_result->fetch_assoc()) {
                  $selected = ($role == $row['position']) ? 'selected' : ''; // Check if the option is selected
                  echo "<option value='{$row['position']}' $selected>{$row['position']}</option>";
                }
              ?>
            </select>
          </div>
          </form>
          
          <table id="meetingsTable">
            <thead>
              <tr>
                <th>ID</th>
                <th>Employee Name</th>
                <th>Employee Type</th>
                <th>Department</th>
                <th>Role</th>
                <th>Contact No.</th>
                <th>Email</th>
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
                        echo "<td>" . sprintf("E%03d", htmlspecialchars($row['officer_id'])) . "</td>";
                        echo "<td>" . $row['fname'] . " " . $row['lname'] . "</td>";
                        echo "<td>" . $row['type'] . "</td>";
                        echo "<td>" . $row['role'] . "</td>";
                        echo "<td>" . $row['position'] . "</td>";
                        echo "<td>" . $row['phone'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>";
                        echo "<a class='edit clickable' data-id='" . $row['officer_id'] . "'>Edit</a>" . " | ";
                        echo "<a class='delete clickable' data-id='" . $row['officer_id'] . "'>Delete</a>";
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
</div>

<!-- New Report Modal -->
<div id="newTransactionModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>New Employee</h2>
      <form id="newTransactionForm" action="/AutoMobile Project/admin/functions/addOfficer.php" method="POST">
        <input type="hidden" id="officerId" name="officerId" />
        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" name="firstName" required />

        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" name="lastName" required />

        <label for="Type">Employee Type:</label>
        <select id="Type" name="Type" required>
        <option value="" disabled selected>Select Type</option>
          <option value="Full Time">Full Time</option>
          <option value="Part Time">Part Time</option>
        </select>

        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required />

        <label for="phone">Phone No:</label>
        <input type="text" id="phone" name="phone" required />

        <label for="role">Role:</label>
        <select id="role" name="role" required>
        <option value="" disabled selected>Select Role</option>
          <option value="admin">Admin</option>
          <option value="mechanic">Mechanic</option>
          <option value="employee">Employee</option>
          <option value="inventory">Inventory</option>
        </select>

        <label for="position">Position:</label>
        <select id="position" name="position" required>
          <option value="" disabled selected>Select Position</option>
          <option value="Manager">Manager</option>
          <option value="Assistant Manager">Assistant Manager</option>
          <option value="Senior Mechanic">Senior Mechanic</option>
          <option value="Junior Mechanic">Junior Mechanic</option>
          <option value="Cashier">Cashier</option>
          <option value="Trainee">Trainee</option>
        </select>

        <label for="password">Password:</label>
        <input type="text" id="password" name="password" required />

        <button type="submit" class="addbtn" name="addOfficer" style="display: none;">Submit</button>
        <button type="submit" class="editbtn" name="editOfficer" style="display: none;">Update</button>
      </form>
    </div>
  </div>

  <!-- Modal for Viewing Report Details -->
  <div id="viewTransactionModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>Employee Details</h2>
      <table id="transactionDetailsTable">
        <tr>
          <th>Employee Name:</th>
          <td><span id="transactionName">-</span></td>
        </tr>
        <tr>
          <th>Employee Type:</th>
          <td><span id="transactionType">-</span></td>
        </tr>
        <tr>
          <th>Start Date:</th>
          <td><span id="transactionDate">-</span></td>
        </tr>
        <tr>
          <th>End Date:</th>
          <td><span id="transactionDate">-</span></td>
        </tr>
        <tr>
          <th>Department:</th>
          <td><span id="transactionAmount">-</span></td>
        </tr>
        <tr>
          <th>Location Name:</th>
          <td><span id="transactionLocation">-</span></td>
        </tr>
        <tr>
          <th>Supervisor:</th>
          <td><span id="transactionManagedBy">-</span></td>
        </tr>
        <tr>
          <th>Role:</th>
          <td><span id="transactionApprovedBy">-</span></td>
        </tr>
      </table>
    </div>
  </div>

  <?php include 'includes/footer.php'; ?> <!-- Include the footer -->

  <script src="/AutoMobile Project/admin/assets/js/index.js"></script>
  <script src="/AutoMobile Project/admin/assets/js/humanResource.js"></script>
</body>

</html>