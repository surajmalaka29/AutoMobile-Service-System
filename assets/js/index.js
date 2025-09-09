document.addEventListener('DOMContentLoaded', function() {
    const wrapper = document.querySelector('.wrapper');
    const registerLink = document.querySelector('.register-link');
    const loginLink = document.querySelector('.login-link');

    // Function to get query parameters from the URL
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    // Show the correct form based on the query parameter
    const formType = getQueryParam("form");
    if (formType === "signup") {
        wrapper.classList.add('active'); // Show signup form
        console.log("Signup form loaded via query parameter");
    } else {
        wrapper.classList.remove('active'); // Default to login form
        console.log("Login form loaded via query parameter");
    }

    // Toggle functionality for Links
    if (registerLink && loginLink) {
      registerLink.addEventListener('click', function(e) {
        e.preventDefault();  // Prevent default link behavior
        wrapper.classList.add('active');
        console.log("Register link clicked");

        // Reset the URL by removing the query parameter
        history.pushState(null, null, window.location.pathname);
      });
  
      loginLink.addEventListener('click', function(e) {
        e.preventDefault();  // Prevent default link behavior
        wrapper.classList.remove('active');
        console.log("Login link clicked");

        // Reset the URL by removing the query parameter
        history.pushState(null, null, window.location.pathname);
      });
    } else {
      console.error('Register or Login link not found');
    }
});

$(document).ready(function(){
    $('.navbar-toggler').click(function(){
        $(this).toggleClass('collapsed');
    });
});

document.getElementById('loginBtn').addEventListener('click', function() {
  // Redirect to another page
  window.location.href = '/AutoMobile Project/login.php';
});

// Login Form
/*document.querySelector(".signup").addEventListener("submit", function (event) {
  const password = document.querySelector('input[name="Password"]').value;
  const confirmPassword = document.querySelector('input[name="Re-Password"]').value;
  const email = document.querySelector('input[name="Email"]').value;

  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (!emailPattern.test(email)) {
      alert("Please enter a valid email address.");
      event.preventDefault(); // Prevent form submission
      return;
  }

  if (password !== confirmPassword) {
      alert("Passwords do not match!");
      event.preventDefault(); // Prevent form submission
      return;
  }

  if (password.length < 8) {
      alert("Password must be at least 8 characters long.");
      event.preventDefault();
      return;
  }

});*/

// Reset Auto fill
document.querySelectorAll('input').forEach(input => {
  input.addEventListener('animationstart', (e) => {
    if (e.animationName === 'onAutoFillStart') {
      input.style.backgroundColor = 'rgba(0, 0, 0, 0.15)';
    }
  });
});

// Functionality to toggle password visibility
document.addEventListener('DOMContentLoaded', function () {
  function initializePasswordToggle(toggleButton, passwordField) {
    toggleButton.addEventListener('click', function (event) {
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
  }

  const passwordField = document.getElementById('password');
  const togglePasswordButton = document.querySelector('.toggle-password');
  initializePasswordToggle(togglePasswordButton, passwordField);

  const passwordField2 = document.getElementById('password2');
    const togglePasswordButton2 = document.querySelector('.toggle-password2');
    initializePasswordToggle(togglePasswordButton2, passwordField2);

    const passwordField3 = document.getElementById('password3');
    const togglePasswordButton3 = document.querySelector('.toggle-password3');
    initializePasswordToggle(togglePasswordButton3, passwordField3);
});