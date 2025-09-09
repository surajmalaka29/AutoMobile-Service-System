document.addEventListener('DOMContentLoaded', function () {
    // Select all dropdowns and date inputs within the form
    const filters = document.querySelectorAll('.filters select, .filters input[type="date"]');
  
    filters.forEach(function (filter) {
      filter.addEventListener('change', function () {
        // Automatically submit the form when a filter is selected or a date is changed
        this.form.submit();
      });
    });
  });
  
  // Handle the search box and suggestions
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