// Booking Form JavaScript with Validation and AJAX Submission

document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.getElementById('bookingForm');
    
    if (bookingForm) {
        bookingForm.addEventListener('submit', handleBookingSubmit);
        
        // Real-time validation
        const inputs = bookingForm.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
        });
    }
});

function validateField(field) {
    const fieldName = field.name;
    const fieldValue = field.value.trim();
    const errorElement = document.getElementById(fieldName + '-error');
    
    // Clear previous error
    if (errorElement) {
        errorElement.textContent = '';
        field.style.borderColor = '';
    }
    
    let isValid = true;
    let errorMessage = '';
    
    switch(fieldName) {
        case 'name':
            if (fieldValue.length < 2) {
                isValid = false;
                errorMessage = 'Name must be at least 2 characters';
            }
            break;
            
        case 'email':
            if (!validateEmail(fieldValue)) {
                isValid = false;
                errorMessage = 'Please enter a valid email address';
            }
            break;
            
        case 'phone':
            if (!validatePhone(fieldValue)) {
                isValid = false;
                errorMessage = 'Please enter a valid phone number';
            }
            break;
            
        case 'destination':
            if (fieldValue === '') {
                isValid = false;
                errorMessage = 'Please select a destination';
            }
            break;
            
        case 'travel_date':
            const selectedDate = new Date(fieldValue);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (fieldValue === '') {
                isValid = false;
                errorMessage = 'Please select a travel date';
            } else if (selectedDate < today) {
                isValid = false;
                errorMessage = 'Travel date must be in the future';
            }
            break;
            
        case 'number_of_persons':
            const numPersons = parseInt(fieldValue);
            if (isNaN(numPersons) || numPersons < 1) {
                isValid = false;
                errorMessage = 'Number of persons must be at least 1';
            }
            break;
    }
    
    if (!isValid && errorElement) {
        errorElement.textContent = errorMessage;
        field.style.borderColor = '#dc3545';
    } else if (isValid) {
        field.style.borderColor = '#28a745';
    }
    
    return isValid;
}

function validateForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isFormValid = true;
    
    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isFormValid = false;
        }
    });
    
    // Additional validation
    const email = form.querySelector('[name="email"]');
    const phone = form.querySelector('[name="phone"]');
    const destination = form.querySelector('[name="destination"]');
    const travelDate = form.querySelector('[name="travel_date"]');
    const numPersons = form.querySelector('[name="number_of_persons"]');
    
    if (email && !validateEmail(email.value.trim())) {
        validateField(email);
        isFormValid = false;
    }
    
    if (phone && !validatePhone(phone.value.trim())) {
        validateField(phone);
        isFormValid = false;
    }
    
    if (destination && destination.value === '') {
        validateField(destination);
        isFormValid = false;
    }
    
    if (travelDate) {
        const selectedDate = new Date(travelDate.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (travelDate.value === '' || selectedDate < today) {
            validateField(travelDate);
            isFormValid = false;
        }
    }
    
    if (numPersons) {
        const num = parseInt(numPersons.value);
        if (isNaN(num) || num < 1) {
            validateField(numPersons);
            isFormValid = false;
        }
    }
    
    return isFormValid;
}

function handleBookingSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const submitButton = form.querySelector('button[type="submit"]');
    const messageDiv = document.getElementById('form-message');
    
    // Validate form
    if (!validateForm(form)) {
        showMessage(messageDiv, 'Please fix the errors in the form', 'error');
        return;
    }
    
    // Disable submit button
    submitButton.disabled = true;
    submitButton.textContent = 'Submitting...';
    
    // Prepare form data
    const formData = new FormData(form);
    
    // AJAX submission
    fetch('api/booking-submit.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showMessage(messageDiv, data.message, 'success');
            form.reset();
            
            // Clear all error messages
            form.querySelectorAll('.error-message').forEach(error => {
                error.textContent = '';
            });
            
            form.querySelectorAll('input, select, textarea').forEach(field => {
                field.style.borderColor = '';
            });
            
            // Scroll to message
            messageDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        } else {
            showMessage(messageDiv, data.message || 'Error submitting booking. Please try again.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage(messageDiv, 'Network error. Please check your connection and try again.', 'error');
    })
    .finally(() => {
        submitButton.disabled = false;
        submitButton.textContent = 'Submit Booking Enquiry';
    });
}

function showMessage(element, message, type) {
    if (!element) return;
    
    element.textContent = message;
    element.className = 'form-message ' + type;
    element.style.display = 'block';
    
    // Auto-hide success messages after 5 seconds
    if (type === 'success') {
        setTimeout(() => {
            element.style.display = 'none';
        }, 5000);
    }
}

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePhone(phone) {
    const re = /^[\d\s\-\+\(\)]+$/;
    return re.test(phone) && phone.replace(/\D/g, '').length >= 10;
}

