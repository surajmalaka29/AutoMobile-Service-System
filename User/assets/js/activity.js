let activityId = null;
let refreshInterval = null; // To store the interval ID

// Function to handle clicks on rows and "More Details" buttons
document.querySelectorAll('.clickable-row, .viewActivityBtn').forEach(function(element) {
    element.addEventListener('click', function(event) {
        event.preventDefault();
        activityId = this.getAttribute('data-bill-id');
        fetchActivityStatus(activityId);
        startPeriodicRefresh(activityId); // Start periodic refresh

        // Hide the meetings table and show the ongoing activities container
        document.querySelector('.activity-container').style.display = 'none';
        document.getElementById('ongoingActivitiesContainer').style.display = 'block';
    });
});

/*// Function to handle row clicks and trigger the corresponding "View Activity" link
document.querySelectorAll('.clickable-row').forEach(function(row) {
    row.addEventListener('click', function() {
        const activityId = this.getAttribute('data-bill-id');
        event.preventDefault();
        fetchActivityStatus(activityId);
        //startPeriodicRefresh(activityId);

        // Hide the meetings table and show the ongoing activities container
        document.querySelector('.activity-container').style.display = 'none';
        document.getElementById('ongoingActivitiesContainer').style.display = 'block';
  });
});*/

// Back button to return to the previous container
function goBack() {
    document.getElementById('ongoingActivitiesContainer').style.display = 'none';
    document.querySelector('.activity-container').style.display = 'block';

    stopPeriodicRefresh(); // Stop periodic refresh when going back
}

/*// Add event listeners for each "More Details" button
document.querySelectorAll('.viewActivityBtn').forEach(function(button) {
    button.addEventListener('click', function() {
        const activityId = this.getAttribute('data-bill-id');
        event.preventDefault();
        fetchActivityStatus(activityId);
        //startPeriodicRefresh(activityId);
        // Hide the meetings table and show the ongoing activities container
        document.querySelector('.activity-container').style.display = 'none';
        document.getElementById('ongoingActivitiesContainer').style.display = 'block';
    });
});*/

// Add event listener for back button
document.getElementById('backButton').addEventListener('click', goBack);



// Function to format the date to YYYY/MM/DD
function formatDate(dateString) {
    const date = new Date(dateString); // Create a Date object from the string
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Get the month and ensure two digits
    const day = String(date.getDate()).padStart(2, '0'); // Get the day and ensure two digits
    
    return `${year}/${month}/${day}`; // Return in the format YYYY/MM/DD
}

// Function to format the time to HH:MM
function formatTime(timeString) {
    const timeParts = timeString.split(':'); // Split the time by the dot (.)
    const hoursMinutes = timeParts.slice(0, 2).join(':'); // Join hours and minutes with colon
    return hoursMinutes; // Return in the format HH:MM
}

function fetchActivityStatus(activityId) {
    // Perform AJAX call to fetch bill details
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "/AutoMobile Project/User/functions/activity_status.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            try {
                const response = JSON.parse(xhr.responseText);
                
                // Validate that the response contains the required fields
                if (response && response.license_no  && response.app_date && response.start_time) {
                    const formattedDate = formatDate(response.app_date);
                    const formattedTime = formatTime(response.start_time);
                    // Update the UI with the activity status
                    document.querySelector('.container-h2').innerText = `${response.license_no} - ${response.activity_type}`;
                    document.querySelector('.date-time1').innerText = `Start Date - ${formattedDate}`;
                    document.querySelector('.date-time2').innerText = `Start Time - ${formattedTime}`;
                    
                    // Handle the status (you can add more timeline items dynamically if needed)
                    const timelineItems = document.querySelectorAll('.timeline-item');
                    let quotationMessage = document.querySelector('.quotation');
                    let buttons = document.querySelector('.button-container');
                    //let quotationMessage = document.querySelector('.quotation');
                    timelineItems.forEach((item, index) => {
                        item.classList.remove('active'); // Clear previous active classes
                        if (index === 0 && response.status === 'Started') {
                            item.classList.add('active');
                        } else if (index === 1 && response.status === 'Preparing Quotation') {
                            item.classList.add('active');
                        } else if (index === 2 && response.status === 'Quotation Sent') {
                            item.classList.add('active');
                            quotationMessage.style.display = 'block';
                            buttons.style.display = 'block';
                        } else if (index === 3 && response.status === 'Service Scheduled') {
                            item.classList.add('active');
                        } else if (index === 4 && response.status === 'Service Completed') {
                            item.classList.add('active');
                        }
                    });

                    // If the status is not "Quotation Sent", hide the quotation message
                    if (response.status !== 'Quotation Sent') {
                        quotationMessage.style.display = 'none';
                        buttons.style.display = 'none';
                    }

                } else {
                    console.error("Missing data in the response", response);
                }
            } catch (error) {
                console.error("Error parsing JSON:", error);
            }
        }
    };
    xhr.send("activity_id=" + activityId);
}


/* Accept Quotation */
document.getElementById("acceptBtn").addEventListener("click", function () {
    if (!activityId) {
        console.error("Activity ID is not set.");
        return;
    }

    // Perform an AJAX call to update the status
    fetch("/AutoMobile Project/User/functions/acceptQuota.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `activity_id=${encodeURIComponent(activityId)}`
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            console.log("Error updating status: " + data.message);
        }
    })
    .catch(error => {
        console.error("Error:", error);
    });
});


/* Reject Quotation */
document.getElementById("declineBtn").addEventListener("click", function () {
    if (!activityId) {
        console.error("Activity ID is not set.");
        return;
    }

    // Perform an AJAX call to update the status
    fetch("/AutoMobile Project/User/functions/rejectQuota.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: `activity_id=${encodeURIComponent(activityId)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = "/AutoMobile Project/User/activity.php"
        } else {
            console.log("Error updating status: " + data.message);
        }

    })
    .catch(error => {
        console.error("Error:", error);
    });
});



/* Periodic Refresh */
// Function to start the periodic refresh
function startPeriodicRefresh(activityId) {
    stopPeriodicRefresh(); // Clear any existing interval
    refreshInterval = setInterval(() => {
        if (activityId) {
            fetchActivityStatus(activityId); // Fetch the updated status periodically
        }
    }, 5000); // Refresh every 5 seconds
}

// Function to stop the periodic refresh
function stopPeriodicRefresh() {
    if (refreshInterval) {
        clearInterval(refreshInterval);
        refreshInterval = null;
    }
}