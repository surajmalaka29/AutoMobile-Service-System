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

  // Check if there's a 'itemName' parameter in the URL and pre-fill the search box
  const urlParams = new URLSearchParams(window.location.search);
  const itemName = urlParams.get('itemName') || ''; // Get 'itemName' from URL or default to empty string
  searchBox.value = itemName; // Pre-fill the search box with the itemName value

  searchBox.addEventListener('input', function () {
    const query = this.value;

    if (query.length < 2) {
      suggestionsContainer.innerHTML = '';
      suggestionsContainer.style.display = 'none'; // Clear suggestions if the query is less than 2 characters
      return;
    }

    fetch(`functions/search_inventory.php?itemName=${encodeURIComponent(query)}`)
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

      // Redirect to filter data based on selected itemName
      const form = document.querySelector('form');
      const url = new URL(form.action);
      url.searchParams.set('itemName', selectedName); // Add the selected itemName to the URL
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
      // Clear the itemName filter if the search box is empty
      const form = document.querySelector('form');
      const url = new URL(form.action);
      url.searchParams.delete('itemName'); // Remove the 'itemName' parameter from the URL
      window.location.href = url.toString(); // Reload the page without the itemName filter
    }
  });
});


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
});


function getItemInfo(item_id) {
  fetch(`/AutoMobile Project/admin/functions/get_item_info.php?item_id=${item_id}`)
    .then(response => response.json())
    .then(data => {
      const form = document.getElementById('addVehicleForm');
      form.itemName.value = data.item_name;
      form.category.value = data.category;
      form.brand.value = data.brand;
      form.quantity.value = data.quantity;
      form.buyingPrice.value = data.unit_buying_price;
      form.sellingPrice.value = data.unit_price;
      form.itemId.value = item_id;
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
      //console.log('delete button clicked');
      const billNo = this.getAttribute('data-id');
      window.location.href = "/AutoMobile Project/admin/functions/addItem.php?item_id=" + billNo;
  });
});

document.querySelectorAll('.edit').forEach(button => {
  button.addEventListener('click', function (event) {
      event.preventDefault();
      //console.log('edit button clicked');
      const billNo = this.getAttribute('data-id');
      getItemInfo(billNo);
  });
});