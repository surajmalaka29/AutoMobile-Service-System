    <?php
        include '../middleware/usermiddleware.php';
        include 'includes/header.php';
        include 'includes/subheader.php';
        include 'includes/sidebar.php';
        include '../config/dbconnection.php';
        include '../functions/alert.php';

        $cus_id = $_SESSION['auth_user']['userId'];

        $stmt = $conn->prepare("SELECT 
                                    a.activity_id,
                                    ap.app_date AS date,
                                    v.category,
                                    v.license_no,
                                    o.fname,
                                    o.lname,
                                    ap.message AS description
                                FROM 
                                    activities a
                                JOIN 
                                    appointment ap ON a.app_id = ap.app_id
                                JOIN 
                                    vehicle v ON ap.vehicle_id = v.vehicle_id
                                JOIN 
                                    officer o ON ap.officer_id = o.officer_id
                                WHERE 
                                    a.status = 'Completed'
                                    AND v.cus_id = ?
                                ORDER BY 
                                    a.activity_id ASC;");
        $stmt->bind_param("i", $cus_id);
        $stmt->execute();
        $query_run = $stmt->get_result();
    ?>
    <link rel="stylesheet" href="/AutoMobile Project/User/assets/css/history.css">

    <div class="col-md-9 flex-grow-1 p-3">
        <div class="text-center">
            <div class="details-container">
                <?php
                if(isset($_SESSION['message'])) { 
                    ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Hey!</strong> <?= $_SESSION['message']; ?>.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php 
                    unset($_SESSION['message']);
                } 
                ?>
                <h2>Service Records</h2>
                <table id="detailsTable">
                    <thead>
                        <tr>
                            <th>Service ID</th>
                            <th>Date</th>
                            <th>License Plate No</th>
                            <th>Category</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // Check if the query returned any results
                            if (mysqli_num_rows($query_run) > 0) {
                                // Loop through the results and create table rows
                                while ($row = mysqli_fetch_assoc($query_run)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['activity_id'] . "</td>";
                                    echo "<td>" . $row['date'] . "</td>";
                                    echo "<td>" . $row['license_no'] . "</td>";
                                    echo "<td>" . $row['category'] . "</td>";
                                    echo '<td><a href="#" class="details-button detailsReportBtn" data-id="' . $row['activity_id'] . '">More Details</a></td>';

                                    echo "</tr>";
                                }
                            } else {
                                // If no data found, display a message
                                echo "<tr><td colspan='5'>No Records found</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>

                <!-- Add space after the table -->
                <div class="details-table-footer">
                    <!-- Horizontal line -->
                    <hr class="details-footer-line">
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Report Modal -->
    <div id="detailsReportModal" class="detailsReportModal">
        <div class="detailsReportModal-content">
            <span class="close" id="closeDetailsReportModal">&times;</span>
            <h2>Detailed Report</h2>
            <!-- Horizontal line -->
            <hr class="details-modal-footer-line">
            <form action="#" method="POST">
                <div class="detailsReportModal-form-container">
                    <div class="details-table">
                        <table>
                            <tbody>
                                <tr>
                                    <td>Service ID </td>
                                    <td> - </td>
                                    <td>001</td>
                                </tr>
                                <tr>
                                    <td>Date </td>
                                    <td> - </td>
                                    <td>19/08/2024</td>
                                </tr>
                                <tr>
                                    <td>Time </td>
                                    <td> - </td>
                                    <td>09.30 - 10.30</td>
                                </tr>
                                <tr>
                                    <td>Category </td>
                                    <td> - </td>
                                    <td>Car</td>
                                </tr>
                                <tr>
                                    <td>License Plate No </td>
                                    <td> - </td>
                                    <td>WP ABC 1234</td>
                                </tr>
                                <tr>
                                    <td>Mechanic Name </td>
                                    <td> - </td>
                                    <td>S.A.N. Lakruwan</td>
                                </tr>
                                <tr>
                                    <td>Service Description </td>
                                    <td> - </td>
                                    <td>Mahaththaya car eka supiriyata service karala dennm ane, baya wenna epa mage pana wage balagannm ane</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
<!-- Closing the row , container divs opened in the sidebar -->
    </div>
</div>

    <script src="/AutoMobile Project/User/assets/js/index.js"></script>
    <script src="/AutoMobile Project/User/assets/js/history.js"></script>
</body>
</html>