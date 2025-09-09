// Functionality to toggle password visibility
document.addEventListener('DOMContentLoaded', function () {
  const passwordField = document.getElementById('password');
  const togglePasswordButton = document.querySelector('.toggle-password');
  
  togglePasswordButton.addEventListener('click', function (event) {
      // Prevent the button from submitting the form
      event.preventDefault();
      
      // Toggle password visibility
      const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordField.setAttribute('type', type);

      // Toggle the icon
      this.innerHTML = type === 'password' 
          ? '<i class="fas fa-eye"></i>'
          : '<i class="fas fa-eye-slash"></i>';
  });
});

// Functionality to toggle password visibility
document.addEventListener('DOMContentLoaded', function () {
  const passwordField = document.getElementById('re-password');
  const togglePasswordButton2 = document.querySelector('.toggle-password2');
  
  togglePasswordButton2.addEventListener('click', function (event) {
      // Prevent the button from submitting the form
      event.preventDefault();
      
      // Toggle password visibility
      const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordField.setAttribute('type', type);

      // Toggle the icon
      this.innerHTML = type === 'password' 
          ? '<i class="fas fa-eye"></i>'
          : '<i class="fas fa-eye-slash"></i>';
  });
});

document.addEventListener('DOMContentLoaded', function() {
  const fileInput = document.getElementById('fileInput');
  const wrapper = document.getElementById('wrapper');

  fileInput.addEventListener('change', function(event) {
      const file = event.target.files[0];
      if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
              wrapper.style.backgroundImage = `url(${e.target.result})`;
              wrapper.style.backgroundSize = 'cover'; // Ensure the image covers the entire wrapper
          };
          reader.readAsDataURL(file);
      }
  });
});