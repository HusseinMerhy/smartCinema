import { apiService } from './apiService.js';

document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    const errorContainer = document.getElementById('error-container');

    const showError = (message) => {
        if (errorContainer) {
            errorContainer.textContent = message;
            errorContainer.style.display = 'block';
        }
    };
    
    if (loginForm) {
        loginForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            if (errorContainer) errorContainer.style.display = 'none';

            const identifier = document.getElementById('identifier').value;
            const password = document.getElementById('password').value;

            try {
                const response = await apiService.post('/users.php?action=login', { identifier, password });

                if (response && response.user) {
                    // --- THE FIX IS HERE ---
                    // First, save the user session data.
                    localStorage.setItem('smart_cinema_user', JSON.stringify(response.user));
                    
                    // Now, check the user's role and redirect accordingly using an absolute path.
                    if (response.user.role === 'admin') {
                        alert('Admin login successful. Redirecting to Admin Panel...');
                        // This absolute path will work from anywhere in the application.
                        window.location.href = '/smartCinema/cinema-client/admin/index.html';
                    } else {
                        alert('Login successful!');
                        window.location.href = '/smartCinema/cinema-client/index.html';
                    }
                } else {
                    showError(response.message || 'Login failed. Please check your credentials.');
                }
            } catch (error) {
                console.error('Login error:', error);
                showError(error.message || 'An error occurred during login.');
            }
        });
    }
});