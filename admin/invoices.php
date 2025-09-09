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
$sql = "SELECT c.fname, c.lname, i.invoice_id, i.date, i.amount, i.status
        FROM invoice i, customer c 
        WHERE i.cus_id = c.cus_id";

// Add filters to the query if values are selected
if ($customer) {
  $sql .= " AND CONCAT(c.fname, ' ', c.lname) = ?";
}
if ($startDate) {
  $sql .= " AND i.date >= ?";
}
if ($endDate) {
  $sql .= " AND i.date <= ?";
}
if ($status) {
  $sql .= " AND i.status = ?";
}
$sql .= " ORDER BY i.invoice_id ASC";

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


/* Getting the total */


// SQL query to calculate the total amount with applied filters
$sql_total = "SELECT SUM(i.amount) AS total_amount
              FROM invoice i, customer c
              WHERE i.cus_id = c.cus_id";

// Apply the same filters as in the main query
if ($customer) {
  $sql_total .= " AND CONCAT(c.fname, ' ', c.lname) = ?";
}
if ($startDate) {
  $sql_total .= " AND i.date >= ?";
}
if ($endDate) {
  $sql_total .= " AND i.date <= ?";
}
if ($status) {
  $sql_total .= " AND i.status = ?";
}

// Prepare the query for the total amount
$stmt_total = $conn->prepare($sql_total);

// Prepare and execute the query for the total amount
$params_total = [];
$types_total = '';

// Add filters to the total amount query
if ($customer) {
  $params_total[] = $customer;
  $types_total .= 's';
}
if ($startDate) {
  $params_total[] = $startDate;
  $types_total .= 's';
}
if ($endDate) {
  $params_total[] = $endDate;
  $types_total .= 's';
}
if ($status) {
  $params_total[] = $status;
  $types_total .= 's';
}

if ($types_total) {
  $stmt_total->bind_param($types_total, ...$params_total);
}

$stmt_total->execute();
$total_result = $stmt_total->get_result();
$total_row = $total_result->fetch_assoc();
$totalAmount = $total_row['total_amount'] ?? 0; // Use 0 if no total amount is found
?>

<body>
  <link rel="stylesheet" href="/AutoMobile Project/admin/assets/css/invoices.css">
  <!-- Wrapper for sidebar and main content -->
  <div class="dashboard-wrapper">
    <!-- import sideMenu.php -->
    <?php include 'includes/sideMenu.php'; ?>

    <!-- Main Content -->
    <div class="content">
      <!-- Navbar -->
      <?php include 'includes/navbar.php'; ?>

      <div class="container">
        <?php include 'includes/alert.php'; ?>
        <header>
          <h2>Invoices Management</h2>
        </header>

        <div class="toolbar">
          <div class="searchOption">
            <input type="text" id="searchBox" placeholder="Search Invoices..." autocomplete="off" />
            <div class="suggestions-box" id="suggestions-box"></div>
          </div>
        </div>
        <form method="POST" action="invoices.php">
          <div class="filters">
            <select id="statusFilter" name="status">
              <option value="" <?php echo empty($status) ? 'selected' : ''; ?>>All Invoices</option>
              <?php
              $statuses = ['Paid', 'Unpaid'];
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
        <div class="button-container">
          <button id="exportBtn">Export CSV</button>
          <!--<button id="pdfBtn">PDF</button>-->
        </div>

        <span id="totalAmount">Total Amount: Rs.<?php echo number_format($totalAmount, 2); ?></span>

        <table id="invoicesTable">
          <thead>
            <tr>
              <th>Invoice ID</th>
              <th>Customer Name</th>
              <th>Date</th>
              <th>Amount</th>
              <th>Status</th>
              <!--<th>Actions</th>-->
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
                echo "<td>" . $row['invoice_id'] . "</td>";
                echo "<td>" . $row['fname'] . " " . $row['lname'] . "</td>";
                echo "<td>" . $row['date'] . "</td>";
                echo "<td>" . $row['amount'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                //echo "<td> Delete </td>";
                echo "</tr>";
              }
            } else {
              // If no data found, display a message
              echo "<tr><td colspan='8' style='text-align: center;'>No Invoices found</td></tr>";
            }
            ?>
          </tbody>
        </table>
      </div>

      <?php
      // Get the total number of records
      $sql_count = "SELECT COUNT(*) AS total FROM invoice";
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
  </div><br>

  <?php include 'includes/footer.php'; ?> <!-- Include the footer -->

  <script src="/AutoMobile Project/admin/assets/js/index.js"></script>
  <script src="/AutoMobile Project/admin/assets/js/invoices.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.6.0/jspdf.umd.min.js"></script>
  <script>
    document.getElementById('exportBtn').addEventListener('click', function() {
      // Create a temporary link to download the CSV
      let downloadLink = document.createElement('a');
      downloadLink.href = '/AutoMobile Project/functions/csvfile.php'; // Your PHP script
      downloadLink.setAttribute('download', 'invoices.csv'); // Optional: Ensures file name
      document.body.appendChild(downloadLink);
      downloadLink.click();
      document.body.removeChild(downloadLink);

      // Redirect after a short delay to ensure download starts
      setTimeout(() => {
        window.location.href = '/AutoMobile Project/admin/invoices.php';
      }, 2000); // Adjust delay if needed
    });
  </script>

</body>

</html>