// Show login modal
function showLogin() {
    document.getElementById('loginModal').style.display = 'block';
}

// Close modal
function closeModal() {
    document.getElementById('loginModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('loginModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

// Email validation
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

// Phone validation
function validatePhone(phone) {
    const re = /^0\d{9}$/;
    return re.test(phone);
}

// Form validation
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    let isValid = true;
    
    // Check email
    const email = form.querySelector('input[type="email"]');
    if (email && !validateEmail(email.value)) {
        showError(email, 'Invalid email format');
        isValid = false;
    }
    
    // Check phone
    const phone = form.querySelector('input[name="phone"]');
    if (phone && !validatePhone(phone.value)) {
        showError(phone, 'Phone must be 10 digits starting with 0');
        isValid = false;
    }
    
    // Check password
    const password = form.querySelector('input[name="password"]');
    if (password && password.value.length < 6) {
        showError(password, 'Password must be at least 6 characters');
        isValid = false;
    }
    
    return isValid;
}

// Show error message
function showError(input, message) {
    const error = document.createElement('div');
    error.className = 'error';
    error.textContent = message;
    input.style.borderColor = 'red';
    
    // Remove existing error
    const existingError = input.nextElementSibling;
    if (existingError && existingError.classList.contains('error')) {
        existingError.remove();
    }
    
    input.parentNode.insertBefore(error, input.nextSibling);
}

// Clear errors on input
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            this.style.borderColor = '#ddd';
            const error = this.nextElementSibling;
            if (error && error.classList.contains('error')) {
                error.remove();
            }
        });
    });
});

// Search events
function searchEvents() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const cards = document.getElementsByClassName('event-card');
    
    for (let i = 0; i < cards.length; i++) {
        const title = cards[i].getElementsByTagName('h3')[0];
        const text = title.textContent || title.innerText;
        
        if (text.toLowerCase().indexOf(filter) > -1) {
            cards[i].style.display = '';
        } else {
            cards[i].style.display = 'none';
        }
    }
}

// Confirm delete
function confirmDelete() {
    return confirm('Are you sure you want to delete this event?');
}