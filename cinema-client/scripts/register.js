document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.getElementById('register-form');

    // This helper function is now part of the script itself
    const showError = (message) => {
        const errorContainer = document.getElementById('error-container');
        if (errorContainer) {
            errorContainer.textContent = message;
            errorContainer.style.display = 'block';
        }
    };

    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            document.getElementById('error-container').style.display = 'none';

            const email = document.getElementById('email').value.trim();
            const phoneNumber = document.getElementById('phoneNumber').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (password !== confirmPassword) {
                return showError("Passwords do not match.");
            }
            if (!phoneNumber) {
                return showError("Phone number is required.");
            }

            try {
                const response = await axios.post('http://localhost/smartCinema/cinema-server/api/users.php?action=register', {
                    email,
                    phoneNumber,
                    password
                });

                alert('Registration successful! Please log in.');
                window.location.href = 'login.html';

            } catch (err) {
                const message = err.response?.data?.message || 'Registration failed.';
                showError(message);
            }
        });
    }
});