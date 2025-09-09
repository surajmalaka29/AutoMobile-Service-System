<?php
  include '../middleware/adminmiddleware.php';
  include 'includes/header.php';
  include '../config/dbconnection.php';

  $cus_id = $_SESSION['auth_user']['userId'];

  // Capture the filter values from the GET request
  $customer = isset($_GET['customer']) ? $_GET['customer'] : '';

  // Get the current page and rows per page from the URL or set default values
  $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Default to page 1
  //$rowsPerPage = isset($_GET['rowsPerPage']) ? (int)$_GET['rowsPerPage'] : 8; // Default to 10 rows per page
  $rowsPerPage = 8; // Fixed rows per page
  
  // Calculate the offset for the SQL query
  $offset = ($page - 1) * $rowsPerPage;

  // Base SQL query
  $sql = "SELECT cus_Id, fname, lname, email, phone, city, membership FROM customer";

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

  // If there are any conditions, append them to the SQL query
  if (count($whereConditions) > 0) {
    $sql .= " WHERE " . implode(" AND ", $whereConditions);
  }

  // Add ORDER BY for sorting
  $sql .= " ORDER BY cus_Id ASC"; // Use DESC for descending order

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
?>

<body>
  <link rel="stylesheet" href="/AutoMobile Project/admin/assets/css/meeting.css">
  <link rel="stylesheet" href="/AutoMobile Project/admin/assets/css/customer.css">

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
          <h2>Customers</h2>
          <div class="toolbar">
            <div class="searchOption">
              <input type="text" id="searchBox" placeholder="Search Customer..." autocomplete="off" />
              <div class="suggestions-box" id="suggestions-box"></div>
            </div>
            <button id="newMeetingBtn">+ New Customer</button>
          </div>
          
          <table id="meetingsTable">
            <thead>
              <tr>
                <th>ID</th>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Contact No.</th>
                <th>City</th>
                <th>Membership</th>
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
                        echo "<td>" . sprintf("E%03d", htmlspecialchars($row['cus_Id'])) . "</td>";
                        echo "<td>" . $row['fname'] . " " . $row['lname'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . (!empty($row['phone']) ? $row['phone'] : ' - ') . "</td>";
                        echo "<td>" . (!empty($row['city']) ? $row['city'] : ' - ') . "</td>";
                        echo "<td>" . (!empty($row['membership']) ? $row['membership'] : ' - ') . "</td>";
                        echo "<td>";
                        echo "<a class='edit clickable' data-id='" . $row['cus_Id'] . "'>Edit</a>" . " | ";
                        echo "<a class='delete clickable' data-id='" . $row['cus_Id'] . "'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                        
                    }
                } else {
                    // If no data found, display a message
                    echo "<tr><td colspan='7'>No Customers found</td></tr>";
                }
              ?>
            </tbody>
          </table>
        </div>
        <?php
          // Get the total number of records
          $sql_count = "SELECT COUNT(*) AS total FROM customer";
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
      <h2>New Customer</h2>
      <form id="newTransactionForm" action="/AutoMobile Project/admin/functions/addCustomer.php" method="POST">
        <input type="hidden" id="customerId" name="customerId" />
        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" name="firstName" required />

        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" name="lastName" required />

        <label for="email">Email:</label>
        <input type="text" id="email" name="email" required />

        <label for="phone">Phone No:</label>
        <input type="text" id="phone" name="phone" required />

        <label for="membership">Membership:</label>
        <input type="text" id="membership" name="membership" />

        <label for="password">Password:</label>
        <input type="text" id="password" name="password" required />

        <button type="submit" class="addbtn" name="addCustomer" style="display: none;">Submit</button>
        <button type="submit" class="editbtn" name="editCustomer" style="display: none;">Update</button>
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
  <script src="/AutoMobile Project/admin/assets/js/customer.js"></script>
</body>

</html>