// smartCinema/cinema-client/scripts/index.js

import { isLoggedIn, logout, getUserInfo } from './auth.js';

function setupIndexHeader() {
    const loginBtn = document.getElementById('login-btn');
    const userMenu = document.getElementById('user-menu');
    const userIcon = document.getElementById('user-icon');
    const userDropdown = document.getElementById('user-dropdown');
    const logoutBtn = document.getElementById('logout-btn');

    if (isLoggedIn()) {
        // User is logged in: show user menu, hide login button
        loginBtn.style.display = 'none';
        userMenu.style.display = 'block';

        // Add event listener for dropdown toggle
        userIcon.addEventListener('click', (event) => {
            event.stopPropagation();
            userDropdown.classList.toggle('show');
        });

        // Add event listener for logout
        logoutBtn.addEventListener('click', (e) => {
            e.preventDefault();
            logout();
            alert("You have been logged out.");
            window.location.reload(); // Reload the page to reset the header
        });

    } else {
        // User is logged out: show login button, hide user menu
        loginBtn.style.display = 'block';
        userMenu.style.display = 'none';
    }

    // Global click listener to close the dropdown
    window.addEventListener('click', () => {
        if (userDropdown.classList.contains('show')) {
            userDropdown.classList.remove('show');
        }
    });
}

// --- Main execution block for the index page ---
document.addEventListener('DOMContentLoaded', () => {
    initSwiper();
    setupIndexHeader();
});