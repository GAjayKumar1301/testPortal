document.addEventListener("DOMContentLoaded", function() {
    // Get all form containers
    const adminForm = document.getElementById('AdminForm');
    const staffForm = document.getElementById('staffForm');
    const studentForm = document.getElementById('studentForm');
    
    // Get all navigation links
    const adminLink = document.getElementById('AdminLink');
    const staffLink = document.getElementById('staffLink');
    const studentLink = document.getElementById('studentLink');
    
    // Initially show admin form and hide others
    adminForm.classList.add('active');
    staffForm.classList.remove('active');
    studentForm.classList.remove('active');
    
    // Add click event listeners to navigation links
    adminLink.addEventListener('click', function() {
        adminForm.classList.add('active');
        staffForm.classList.remove('active');
        studentForm.classList.remove('active');
    });
    
    staffLink.addEventListener('click', function() {
        adminForm.classList.remove('active');
        staffForm.classList.add('active');
        studentForm.classList.remove('active');
    });
    
    studentLink.addEventListener('click', function() {
        adminForm.classList.remove('active');
        staffForm.classList.remove('active');
        studentForm.classList.add('active');
    });
});

function showError(formElement, message) {
    const msgElement = formElement.querySelector('.msg');
    msgElement.textContent = message;
    msgElement.className = 'msg error';
    
    // Add shake animation
    formElement.classList.add('shake');
    setTimeout(() => {
        formElement.classList.remove('shake');
    }, 500);
}

// Basic login validation with sample credentials
function login(role) {
    let formElement, username, password;

    if (role === 'admin') {
        formElement = document.getElementById('AdminForm');
        username = document.getElementById('adminUser').value;
        password = document.getElementById('adminPass').value;
        if (username === 'admin123' && password === 'Reset1998') {
            document.getElementById('msg').className = 'msg success';
            document.getElementById('msg').textContent = '✅ Admin Login Successful!';
            setTimeout(function() {
                window.location.href = 'StudentDashboard.html';
            }, 1000);
        } else {
            showError(formElement, '❌ Invalid Admin Credentials!');
        }
    } 
    else if (role === 'staff') {
        formElement = document.getElementById('staffForm');
        username = document.getElementById('staffUser').value;
        password = document.getElementById('staffPass').value;
        if (username === 'staff001' && password === 'Reset1998') {
            document.getElementById('msg').className = 'msg success';
            document.getElementById('msg').textContent = '✅ Staff Login Successful!';
            setTimeout(function() {
                window.location.href = 'StudentDashboard.html';
            }, 1000);
        } else {
            showError(formElement, '❌ Invalid Staff Credentials!');
        }
    } 
    else if (role === 'student') {
        formElement = document.getElementById('studentForm');
        username = document.getElementById('studentUser').value;
        password = document.getElementById('studentPass').value;
        if (username === '22691a3108' && password === 'Reset1998') {
            document.getElementById('msg').className = 'msg success';
            document.getElementById('msg').textContent = '✅ Student Login Successful!';
            setTimeout(function() {
                window.location.href = 'StudentDashboard.html';
            }, 1000);
        } else {
            showError(formElement, '❌ Invalid Student Credentials!');
        }
    }

    // Prevent form submission
    return false;
}