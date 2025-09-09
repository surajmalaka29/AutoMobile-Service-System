// Get the modals
var addVehicleModal = document.getElementById('addVehicleModal');
var editVehicleModal = document.getElementById('editVehicleModal');

// Get the buttons that open the modals
var addVehicleBtn = document.getElementById('addVehicleBtn');
var editVehicleBtn = document.getElementById('editVehicleBtn');

// Get the <span> elements that close the modals
var closeAddModal = document.getElementById('closeAddModal');
var closeEditModal = document.getElementById('closeEditModal');

// Get the submit button
var submitModalBtn = document.getElementById('submitModalBtn');

var form = null; // Initialize form variable to null

// Function to open the specified modal
function openModal(modal) {
    modal.style.display = 'flex'; // Display as flex to center it properly
}

// Function to close the specified modal
function closeModal(modal) {
    modal.style.display = 'none';
}

// When the user clicks the respective button, open the corresponding modal
addVehicleBtn.onclick = function() {
    event.preventDefault(); // Prevent default link behavior
    openModal(addVehicleModal);
}

editVehicleBtn.onclick = function() {
    event.preventDefault(); // Prevent default link behavior
    openModal(editVehicleModal);
}

// When the user clicks the close button, close the respective modal
closeAddModal.onclick = function() {
    closeModal(addVehicleModal);
}

closeEditModal.onclick = function() {
    closeModal(editVehicleModal);
}

// When the user clicks anywhere outside of the modal content, close it
window.onclick = function(event) {
    if (event.target == addVehicleModal) {
        closeModal(addVehicleModal);
    } else if (event.target == editVehicleModal) {
        closeModal(editVehicleModal);
    }
}

// Add event listener for the submit button
submitModalBtn.onclick = function() {
    form = document.querySelector('#addVehicleModal form');
    // Perform form validation or processing here
    if (form.checkValidity()) {
        // Form is valid; you can submit the form or handle it as needed
        form.submit();
        closeModal(addVehicleModal); // Close the modal after submission
    } else {
        // Handle invalid form case (e.g., show a message to the user)
        //alert('Please fill in all required fields.');
    }
}

document.getElementById('selectVehicle').addEventListener('change', function() {
    var vehicleId = this.value;
    
    // AJAX request to fetch vehicle details
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/AutoMobile Project/User/functions/addvehicle.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            console.log(xhr.responseText);
            try {
                var vehicleData = JSON.parse(xhr.responseText);
                // Populate form fields
                document.getElementById('editCompany').value = vehicleData.company;
                document.getElementById('editModel').value = vehicleData.model;
                document.getElementById('editManufacturedYear').value = vehicleData.year;
                document.getElementById('editCategory').value = vehicleData.category;
                document.getElementById('editLicensePlateNo').value = vehicleData.license_no;
                document.getElementById('editEngineNo').value = vehicleData.engine_no;
                document.getElementById('editChassisNo').value = vehicleData.chasis_no;
            } catch (e) {
                console.error("Parsing error: ", e);
                console.error("Response received: ", xhr.responseText);
            }
        }
    };
    
    xhr.send("vehicle_id=" + vehicleId);
});

// License Plate Validation
document.getElementById('licensePlateNo').addEventListener('input', function (e) {
    let value = e.target.value;

    value = value.replace(/[^A-Za-z0-9 -]/g, '');

    value = value.toUpperCase();

    let letters = value.replace(/[^A-Z]/g, ''); // Extract only letters
    let numbers = value.replace(/[^0-9]/g, ''); // Extract only numbers

    if (letters.length > 3) {
        letters = letters.slice(0, 3);
    }

    if (letters.length === 2 || letters.length === 3) {
        value = letters + ' - ';
    } else {
        value = letters;
    }

    value += numbers.slice(0, 4);

    e.target.value = value;
});

// Handle backspacing over the dash and spaces
document.getElementById('licensePlateNo').addEventListener('keydown', function (e) {
    const input = e.target;
    const cursorPosition = input.selectionStart;

    // Check if the user is backspacing over the dash or spaces
    if (e.key === 'Backspace' && (cursorPosition === 5 || cursorPosition === 4)) {
        // Remove the dash and spaces
        const value = input.value;
        input.value = value.slice(0, cursorPosition - 3) + value.slice(cursorPosition);
        input.setSelectionRange(cursorPosition - 3, cursorPosition - 3); // Move cursor appropriately
        e.preventDefault();
    }
});


// Capitalize letters for Engine Number
function handleEngineNoInput(inputElement) {
    inputElement.addEventListener('input', function (e) {
        let value = e.target.value.toUpperCase();
        value = value.slice(0, 12);

        if (value.length >= 6) {
            e.target.setCustomValidity('');  // Reset custom validity message
        } else {
            e.target.setCustomValidity('Engine Number must be between 6 and 12 characters.');
        }
        e.target.value = value;  // Apply the formatted value
    });
}
const engineNoInput = document.getElementById('engineNo');
const engineNoInputEdit = document.getElementById('editEngineNo');
handleEngineNoInput(engineNoInput);
handleEngineNoInput(engineNoInputEdit);


// Capitalize letters for Chassis Number
function handleChassisNoInput(inputElement) {
    inputElement.addEventListener('input', function (e) {
        let value = e.target.value.toUpperCase();
        value = value.slice(0, 17);

        if (value.length === 17) {
            e.target.setCustomValidity('');  // Reset validity if it's 17 characters
        } else {
            e.target.setCustomValidity('Chassis Number must be exactly 17 characters.');
        }
        e.target.value = value;  // Set the formatted value
    });
}

const chassisNoInput = document.getElementById('chassisNo');
const chassisNoInputEdit = document.getElementById('editChassisNo');
handleChassisNoInput(chassisNoInput);
handleChassisNoInput(chassisNoInputEdit);


// Reusable function to capitalize the first letter of the input value
function capitalizeFirstLetter(inputElement) {
    let value = inputElement.value.trim();
    if (value.length > 0) {
        inputElement.value = value.charAt(0).toUpperCase() + value.slice(1).toLowerCase();
    }
}

// Apply to both the company and model input fields
document.addEventListener('DOMContentLoaded', function() {
    const companyInput = document.getElementById('company');
    const modelInput = document.getElementById('model');
    const editCompanyInput = document.getElementById('editCompany');
    const editModelInput = document.getElementById('editModel');

    if (companyInput) {
        companyInput.addEventListener('input', function() {
            capitalizeFirstLetter(companyInput);
        });
    }

    if (modelInput) {
        modelInput.addEventListener('input', function() {
            capitalizeFirstLetter(modelInput);
        });
    }

    if (editCompanyInput) {
        editCompanyInput.addEventListener('input', function() {
            capitalizeFirstLetter(editCompanyInput);
        });
    }

    if (editModelInput) {
        editModelInput.addEventListener('input', function() {
            capitalizeFirstLetter(editModelInput);
        });
    }
});


// Reusable function to validate the manufactured year
function validateManufacturedYear(inputElement) {
    const currentYear = new Date().getFullYear();
    let year = inputElement.value;

    year = year.replace(/\D/g, '');

    if (year.length > 4) {
        year = year.slice(0, 4);
    }

    // Set the input value to the cleaned-up year
    inputElement.value = year;

    if (year.length === 4) {
        if (year < 1900 || year > currentYear) {
            inputElement.setCustomValidity(`Please enter a year between 1900 and ${currentYear}.`);
        } else {
            inputElement.setCustomValidity(""); // Reset custom validity
        }
    } else {
        inputElement.setCustomValidity("Please enter a valid 4-digit year.");
    }
}

// Apply to both the manufactured year input fields
document.addEventListener('DOMContentLoaded', function() {
    const manufacturedYearInput = document.getElementById('manufacturedYear');
    const editManufacturedYearInput = document.getElementById('editManufacturedYear');

    if (manufacturedYearInput) {
        manufacturedYearInput.addEventListener('input', function() {
            validateManufacturedYear(manufacturedYearInput);
        });
    }

    if (editManufacturedYearInput) {
        editManufacturedYearInput.addEventListener('input', function() {
            validateManufacturedYear(editManufacturedYearInput);
        });
    }
});