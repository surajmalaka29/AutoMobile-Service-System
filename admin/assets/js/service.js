const searchBox = document.getElementById('searchBox');document.addEventListener('DOMContentLoaded', function() {
    const suggestionsContainer = document.getElementById('suggestions-box');
  
    // Check if there's a 'service' parameter in the URL and pre-fill the search box
    const urlParams = new URLSearchParams(window.location.search);
    const service = urlParams.get('service') || ''; // Get 'service' from URL or default to empty string
    searchBox.value = service; // Pre-fill the search box with the service value
  
    searchBox.addEventListener('input', function () {
      const query = this.value;
  
      if (query.length < 2) {
        suggestionsContainer.innerHTML = '';
        suggestionsContainer.style.display = 'none'; // Clear suggestions if the query is less than 2 characters
        return;
      }
  
      fetch(`functions/search_services.php?service=${encodeURIComponent(query)}`)
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
  
        // Redirect to filter data based on selected service
        const form = document.querySelector('form');
        const url = new URL(form.action);
        url.searchParams.set('service', selectedName); // Add the selected service to the URL
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
        // Clear the service filter if the search box is empty
        const form = document.querySelector('form');
        const url = new URL(form.action);
        url.searchParams.delete('service'); // Remove the 'service' parameter from the URL
        window.location.href = url.toString(); // Reload the page without the service filter
      }
    });
  });
  
  
  const newTransactionModal = document.getElementById('newTransactionModal');  // New Transaction Modal
  const viewTransactionModal = document.getElementById('viewTransactionModal'); // View Transaction Modal
  const closeNewTransactionModal = document.getElementsByClassName('close')[0]; // Close button inside new transaction modal
  const closeViewTransactionModal = document.getElementsByClassName('close')[1]; // Close button inside view transaction modal
  const newOfficerBtn = document.getElementById('newMeetingBtn'); // New Officer button
  const addButton = document.querySelector(".addbtn");
  const editButton = document.querySelector(".editbtn");
  
  // Ensure modal is hidden on page load
  newTransactionModal.style.display = "none";
  
  // Open modal on "New Transaction" button click
  newOfficerBtn.addEventListener('click', function() {
    const form = document.getElementById('newTransactionForm');
    form.reset();
    newTransactionModal.style.display = 'block';
    editButton.style.display = "none";
    addButton.style.display = "inline-block";
  });
  
  // Close New Transaction Modal when the user clicks on <span> (x)
  closeNewTransactionModal.onclick = function() {
    newTransactionModal.style.display = 'none';
  };
  
  // Close View Transaction Modal when the user clicks on <span> (x)
  closeViewTransactionModal.onclick = function() {
    viewTransactionModal.style.display = 'none';
  };
  
  // Close modal when the user clicks outside of the modal
  window.onclick = function(event) {
    if (event.target === newTransactionModal) {
        newTransactionModal.style.display = 'none';
    }
    if (event.target === viewTransactionModal) {
        viewTransactionModal.style.display = 'none';
    }
  };
  
  
  function getServiceInfo(service_id) {
    fetch(`/AutoMobile Project/admin/functions/get_service_info.php?service_id=${service_id}`)
      .then(response => response.json())
      .then(data => {
        const form = document.getElementById('newTransactionForm');
        form.serviceName.value = data.service_type;
        form.hours.value = data.hours;
        form.serviceCharge.value = data.service_charge;
        form.serviceId.value = service_id;
        const modal = document.getElementById('newTransactionModal');
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
        const serviceId = this.getAttribute('data-id');
        window.location.href = "/AutoMobile Project/admin/functions/addservice.php?service_id=" + serviceId;
    });
  });
  
  document.querySelectorAll('.edit').forEach(button => {
    button.addEventListener('click', function (event) {
        event.preventDefault();
        //console.log('edit button clicked');
        const serviceId = this.getAttribute('data-id');
        getServiceInfo(serviceId);
    });
});