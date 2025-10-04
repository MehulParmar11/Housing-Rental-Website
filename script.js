window.onload = function() {
    // Check if a message is available in the query string
    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('message');

    if (message) {
        // Display the message in the message div
        document.getElementById('message').textContent = message;
    }
};


function validateForm() {
    // Get form elements
    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;
    var phone = document.getElementById("phone").value;
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirm-password").value;

    // Validate Name
    if (name == "") {
        alert("Name must be filled out.");
        return false;
    }

    // Validate Email using regex for format
    var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!email.match(emailPattern)) {
        alert("Please enter a valid email address.");
        return false;
    }

    // Validate Phone Number (Basic check: at least 10 digits)
    var phonePattern = /^[0-9]{10}$/;
    if (!phone.match(phonePattern)) {
        alert("Please enter a valid phone number with at least 10 digits.");
        return false;
    }

    // Validate Password (Basic check: at least 6 characters)
    if (password.length < 6) {
        alert("Password must be at least 6 characters long.");
        return false;
    }

    // Validate that Password and Confirm Password match
    if (password != confirmPassword) {
        alert("Passwords do not match.");
        return false;
    }

    // If all validations pass, show success message and redirect
    alert("Registration successful! You will be redirected to the login page.");
    
    // Redirect to login page after 2 seconds
    setTimeout(function() {
        window.location.href = "login.html";  // Replace with the actual login page URL
    }, 2000); // 2000 milliseconds = 2 seconds

    return false;  // Prevent the form from submitting (since we handle the redirect manually)
}

// Add JavaScript for toggle functionality if you want to use clicks instead of hover
document.querySelector('.dropdown-btn').addEventListener('click', function(event) {
    const dropdownContent = document.querySelector('.dropdown-content');
    dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
    
    // Prevent the link from navigating to another page
    event.preventDefault();
  });
  
const areas = {
    Ahmedabad: ["Bodakdev", "Thaltej", "Satellite"],
    Vadodara: ["Akota", "Bhayli", "Fategunj"],
    Surat: ["Adajan", "Dumas", "Ghod Dod Road"],
    Rajkot: ["Kalavad Road", "Race Course", "Shastri Nagar"],
    Bhavnagar: ["Palitana", "Gadhsisha", "Mahuva"],
    Gandhinagar: ["Sector 16", "Koba", "Sargasan"]
};

const cityInput = document.getElementById('city-input');
const areaSelect = document.getElementById('area-select');
const properties = document.querySelectorAll('.property');
const priceText = property.querySelector('.price').textContent;
const locationText = property.querySelector('.location').textContent;
cityInput.addEventListener('input', function() {
    const selectedCity = cityInput.value;
    areaSelect.innerHTML = '<option value="">Select Area</option>'; // Reset options

    if (areas[selectedCity]) {
        areas[selectedCity].forEach(area => {
            const option = document.createElement('option');
            option.value = area;
            option.textContent = area;
            areaSelect.appendChild(option);
        });
    }

});
