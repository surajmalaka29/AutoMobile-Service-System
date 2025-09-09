document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById("addVehicleModal");
  const btn = document.getElementById("newMeetingBtn");
  const closeButton = document.getElementById("closeAddVehicleModal");
  const addButton = document.querySelector(".addbtn");
  const editButton = document.querySelector(".editbtn");

  // Ensure modal is hidden on page load
  modal.style.display = "none";

  // Event listener to open the modal
  btn.addEventListener('click', function() {
      const form = document.getElementById('addVehicleForm');
      form.reset();
      modal.style.display = "block";
      editButton.style.display = "none";
      addButton.style.display = "inline-block";
  });

  // Event listener to close the modal
  closeButton.addEventListener('click', function() {
      modal.style.display = "none";
  });

  // Close the modal when clicking outside of it
  window.addEventListener('click', function(event) {
      if (event.target === modal) {
          modal.style.display = "none";
      }
  });

  // View Customer Modal
  const viewCustomerModal = document.getElementById("viewCustomerModal1");
  const closeCustomerModalButton = document.getElementById("closeCustomerModal1");
  const closeCustomerDetailsBtn = document.getElementById("closeCustomerDetailsBtn1");

  // Ensure view customer modal is hidden on page load
  viewCustomerModal.style.display = "none";

  // Event listener to open the view customer modal
  document.querySelectorAll('.customer-link').forEach(function(link) {
      link.addEventListener('click', function(event) {
          event.preventDefault();
          viewCustomerModal.style.display = "block";
      });
  });

  // Event listener to close the view customer modal
  closeCustomerModalButton.addEventListener('click', function() {
      viewCustomerModal.style.display = "none";
  });

  closeCustomerDetailsBtn.addEventListener('click', function() {
      viewCustomerModal.style.display = "none";
  });

  // Close the view customer modal when clicking outside of it
  window.addEventListener('click', function(event) {
      if (event.target === viewCustomerModal) {
          viewCustomerModal.style.display = "none";
      }
  });
});


/* Backend */
document.addEventListener('DOMContentLoaded', function() {
  // Select all dropdowns
  const filters = document.querySelectorAll('.filters select');

  filters.forEach(function(filter) {
    filter.addEventListener('change', function() {
      // Automatically submit the form when a filter is selected
      this.form.submit();
    });
  });
});

document.addEventListener('DOMContentLoaded', function() {
  const searchBox = document.getElementById('searchBox');
  const suggestionsContainer = document.getElementById('suggestions-box');

  // Check if there's a 'customer' parameter in the URL and pre-fill the search box
  const urlParams = new URLSearchParams(window.location.search);
  const customer = urlParams.get('customer') || ''; // Get 'customer' from URL or default to empty string
  searchBox.value = customer; // Pre-fill the search box with the customer value

  searchBox.addEventListener('input', function () {
    const query = this.value;

    if (query.length < 2) {
      suggestionsContainer.innerHTML = '';
      suggestionsContainer.style.display = 'none'; // Clear suggestions if the query is less than 2 characters
      return;
    }

    fetch(`functions/search_customers.php?customer=${encodeURIComponent(query)}`)
      .then(response => response.json())
      .then(data => {
        suggestionsContainer.innerHTML = data
          .map(name => `<div>${name}</div>`)
          .join('');
        suggestionsContainer.style.display = 'block'; // Show suggestions
      });
  });

  suggestionsContainer.addEventListener('click', function (event) {
    if (event.target.tagName === 'DIV') {
      const selectedName = event.target.textContent;
      searchBox.value = selectedName; // Set the search box value to the selected name
      suggestionsContainer.innerHTML = '';
      suggestionsContainer.style.display = 'none'; // Hide suggestions

      // Redirect to filter data based on selected customer
      const form = document.querySelector('form');
      const url = new URL(form.action);
      url.searchParams.set('customer', selectedName); // Add the selected customer to the URL
      window.location.href = url.toString(); // Redirect to the updated URL
    }
  });

  document.addEventListener('click', (e) => {
    if (!suggestionsContainer.contains(e.target) && e.target !== searchBox) {
      suggestionsContainer.innerHTML = ''; // Close suggestions if clicked outside
      suggestionsContainer.style.display = 'none'; // Hide suggestions
    }
  });

  // Check if the search box is cleared
  searchBox.addEventListener('input', function () {
    if (this.value === '') {
      // Clear the customer filter if the search box is empty
      const form = document.querySelector('form');
      const url = new URL(form.action);
      url.searchParams.delete('customer'); // Remove the 'customer' parameter from the URL
      window.location.href = url.toString(); // Reload the page without the customer filter
    }
  });
});


function getVehicleInfo(vehicle_id) {
  fetch(`/AutoMobile Project/admin/functions/get_vehicle_info.php?vehicle_id=${vehicle_id}`)
    .then(response => response.json())
    .then(data => {
      const form = document.getElementById('addVehicleForm');
      form.vehicleCustomerName.value = data.cus_id;
      form.vehicleRegistration.value = data.license_no;
      form.vehicleManufacturer.value = data.company;
      form.vehicleModel.value = data.model;
      form.vehicleYear.value = data.year;
      form.chasissNo.value = data.chasis_no;
      form.engineNo.value = data.engine_no;
      form.category.value = data.category;
      form.vehicleId.value = vehicle_id;
      const modal = document.getElementById('addVehicleModal');
      modal.style.display = 'block';
      const editButton = document.querySelector(".editbtn");
      const addButton = document.querySelector(".addbtn");
      addButton.style.display = "none";
      editButton.style.display = "inline-block";
    });
}


document.querySelectorAll('.delete').forEach(button => {
  button.addEventListener('click', function (event) {
      event.preventDefault();
      console.log('delete button clicked');
      const vehicleId = this.getAttribute('data-id');
      window.location.href = "/AutoMobile Project/admin/functions/addvehicle.php?vehicle_id=" + vehicleId;
  });
});

document.querySelectorAll('.edit').forEach(button => {
  button.addEventListener('click', function (event) {
      event.preventDefault();
      console.log('edit button clicked');
      const vehicleId = this.getAttribute('data-id');
      getVehicleInfo(vehicleId);
  });
});