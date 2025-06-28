const API_BASE_URL = 'http://localhost/smartCinema/cinema-server';

document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.getElementById('register-form');
    
    if (registerForm) {
        registerForm.addEventListener('submit', handleRegisterSubmit);
    }
    
    // Logic to toggle password visibility
    const togglePassword = document.getElementById('toggle-password');
    if (togglePassword) {
        togglePassword.addEventListener('click', function () {
            const password = document.getElementById('password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // Toggles the icon between 'show' and 'hide'
            this.classList.toggle('bx-hide');
        });
    }
});
// FILE: cinema-client/scripts/main.js

// Import the API functions we defined
import { getAllMovies, registerUser, loginUser } from './api.js';

// This event listener ensures our code runs after the HTML document is fully loaded.
document.addEventListener('DOMContentLoaded', () => {

    // --- Check which page we are on ---
    // The `window.location.pathname` gives us the path of the current file.
    const path = window.location.pathname;

    if (path.endsWith('index.html') || path.endsWith('/')) {
        // We are on the homepage
        loadMovies();
    } else if (path.endsWith('register.html')) {
        // We are on the registration page
        setupRegistrationForm();
    } else if (path.endsWith('login.html')) {
        // We are on the login page
        setupLoginForm();
    }
});


/**
 * Fetches movies from the API and displays them on the homepage.
 */
async function loadMovies() {
    const nowShowingContainer = document.getElementById('now-showing-movies');
    if (!nowShowingContainer) return; // Exit if the container isn't on the page

    try {
        const movies = await getAllMovies();
        nowShowingContainer.innerHTML = ''; // Clear any placeholder content

        movies.forEach(movie => {
            const movieCard = `
                <div class="movie-card">
                    <img src="assets/posters/${movie.poster_url}" alt="${movie.title}">
                    <div class="movie-card-info">
                        <h5>${movie.title}</h5>
                        <p>${movie.genre}</p>
                        <a href="movie.html?id=${movie.id}" class="btn-book">Book Now</a>
                    </div>
                </div>
            `;
            nowShowingContainer.innerHTML += movieCard;
        });
    } catch (error) {
        console.error('Failed to load movies:', error);
        nowShowingContainer.innerHTML = '<p>Could not load movies at this time. Please try again later.</p>';
    }
}

/**
 * Sets up the event listener for the registration form.
 */
function setupRegistrationForm() {
    const form = document.getElementById('register-form');
    if (!form) return;

    form.addEventListener('submit', async (event) => {
        event.preventDefault(); // Prevent the default form submission

        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const phone = document.getElementById('phone').value;
        const password = document.getElementById('password').value;
        const messageDiv = document.getElementById('form-message');

        try {
            const result = await registerUser(name, email, phone, password);
            messageDiv.textContent = result.success || 'Registration successful!';
            messageDiv.className = 'message-success';
            // Redirect to login page after a short delay
            setTimeout(() => {
                window.location.href = 'login.html';
            }, 2000);
        } catch (error) {
            messageDiv.textContent = error.error || 'Registration failed.';
            messageDiv.className = 'message-error';
        }
    });
}

/**
 * Sets up the event listener for the login form.
 */
function setupLoginForm() {
    const form = document.getElementById('login-form');
    if (!form) return;

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const credential = document.getElementById('credential').value;
        const password = document.getElementById('password').value;
        const messageDiv = document.getElementById('form-message');

        try {
            const result = await loginUser(credential, password);
            messageDiv.textContent = result.success || 'Login successful!';
            messageDiv.className = 'message-success';
            
            // In a real app, you would save the user data/token
            // For now, we'll just redirect to the homepage
            setTimeout(() => {
                window.location.href = 'index.html';
            }, 2000);

        } catch (error) {
            messageDiv.textContent = error.error || 'Login failed.';
            messageDiv.className = 'message-error';
        }
    });
}