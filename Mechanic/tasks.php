<?php
    session_start();
    include '../middleware/mechanicmiddleware.php';
    include 'includes/header.php';
    include 'includes/subheader.php';
    include 'includes/sidebar.php';
    include '../config/dbconnection.php';
    include '../functions/alert.php';

    $officer_id = $_SESSION['auth_user']['userId'];

    $stmt = $conn->prepare("SELECT a.activity_id, ap.app_time, v.category, v.model, ap.app_date FROM appointment ap JOIN activities a ON ap.app_id = a.app_id JOIN vehicle v ON ap.vehicle_id = v.vehicle_id WHERE a.status != 'Completed' AND a.status != 'Rejected' AND ap.officer_id = ? ORDER BY a.activity_id ASC;");
    $stmt->bind_param("i", $officer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>
    <link rel="stylesheet" href="../Mechanic/assets/css/tasks.css">

    <div class="col-md-9 flex-grow-1 p-3">
        <div class="text-center">
            <div class="activity-container">
                <h2 class="container-h2-main">Task</h2>
                <hr class="modal-footer-line">
                <table id="meetingsTable">
                    <thead>
                        <tr>
                            <th>Task ID</th>
                            <th>Category</th>
                            <th>Modal</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr class='clickable-row' data-id=" . $row['activity_id'] . ">";
                                echo "<td>" . $row['activity_id'] . "</td>";
                                echo "<td>" . $row['category'] . "</td>";
                                echo "<td>" . $row['model'] . "</td>";
                                echo "<td>" . $row['app_date'] . "</td>";
                                echo "<td>" . $row['app_time'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No appointments found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <!-- Add space after the table -->
                <div class="table-footer">
                    <!-- Horizontal line -->
                    <hr class="footer-line">
                </div>
            </div>

            <div class="activity-container" id="ongoingActivitiesContainer" style="display: none;">
                <h2 class="container-h2">Task ID - 3191341</h2>
                <!-- Add back button to right side to go back to the previous page -->
                <button id="backBtn" class="backBtn">Back</button>
                <hr class="modal-footer-line">
                <div class="desc-container" style="text-align: left;">
                    <p class="container-description" style="font-size: 18px; color: #ffffff;">Description</p>
                    <p class="description" style="font-size: 18px; color: #ccc;"></p><br>
                </div>

                <!-- Add Next and Previous buttons to navigate between activities -->
                <div class="activity-actions">
                    <button id="nextBtn" class="nextBtn">Next</button>
                    <button id="finishBtn" class="finishBtn">Finish</button>
                </div>

                <!-- Add space -->
                <div class="space"></div>
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="circle"></div>
                        <p>Started</p>
                    </div>
                    <div class="timeline-item">
                        <div class="circle"></div>
                        <p>Preparing Quotation</p>
                        <button id="quotationBtn" class="quotationBtn move-right move-down">Add Quotation</button>
                        <!--<button id="sendQuota" class="quotationBtn2 move-right move-down">Send</button>-->
                    </div>
                    <div class="timeline-item">
                        <div class="circle"></div>
                        <p>Quotation Sent</p>
                    </div>
                    <div class="timeline-item">
                        <div class="circle"></div>
                        <p>Service Scheduled</p>
                    </div>
                    <div class="timeline-item">
                        <div class="circle"></div>
                        <p>Service Completed</p>
                    </div>
                    <div class="timeline-last-item">
                        <div class="circle"></div>
                        <p>Feedback Received</p>
                    </div><br><br>
                    <!-- Horizontal line -->
                    <hr class="timeLine-line">
                </div>
            </div>

            <div id="addQuotation-modal" class="activity-container">
                <h2 class="container-h2" id="taskId">Task ID - 3191341</h2>
                <h3 class="container-h3">Add a Quotation</h3>
                <hr class="modal-footer-line">
                <div class="search-bar" style="display: flex; margin-bottom: 20px; position: absolute; margin-left: 80px;">
                    <input type="search" id="searchBox" placeholder="Search Items">
                    <button><i class="fas fa-search"></i></button>
                    <div class="suggestions-box"></div>
                </div><br><br>
                <table id="meetingsTable" class="quotationstable">
                    <thead>
                        <tr>
                            <th>Item ID</th>
                            <th>Item Name</th>
                            <th>Qty.</th>
                            <th>Price</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <!-- The Content is loaded Using JS -->
                    </tbody>
                </table>
                <!-- Send Quotation Button -->
                <div class="quotation-actions">
                    <button id="sendQuotationBtn" class="send-quotationBtn">Send Quotation</button>
                </div><br>
                <!-- Add space after the table -->
                <div class="table-footer">
                    <!-- Horizontal line -->
                    <hr class="footer-line">
                </div>
            </div>
        </div>
    </div>

    <!-- Closing the row , container divs opened in the sidebar -->
    </div>
    </div>
    <script src="/AutoMobile Project/Mechanic/assets/js/index.js"></script>
    <script src="/AutoMobile Project/Mechanic/assets/js/tasks.js"></script>
    </body>

    </html>