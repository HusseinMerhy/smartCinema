// smartCinema/cinema-client/scripts/auth.js

const USER_KEY = 'smart_cinema_user';

export function login(userData) {
    if (!userData || typeof userData.id === 'undefined') {
        console.error("Login failed: The user data from the server is invalid or missing an ID.");
        return;
    }
    localStorage.setItem(USER_KEY, JSON.stringify(userData));
    // Redirect to the main index page to ensure a fresh start
    window.location.href = '../index.html';
}


export function logout() {
    const wasLoggedIn = !!localStorage.getItem(USER_KEY);
    localStorage.removeItem(USER_KEY);

    if (wasLoggedIn) {
        alert("You have been logged out.");
        // Force a hard reload from the server to clear all state
        window.location.replace(window.location.origin + '/smartCinema/cinema-client/index.html');
    }
}

export function getUserInfo() {
    const userString = localStorage.getItem(USER_KEY);
    if (!userString) return null;
    try {
        const userData = JSON.parse(userString);
        // If the stored data is somehow invalid, clear it
        if (typeof userData.id === 'undefined') {
            throw new Error("Stored user data is invalid.");
        }
        return userData;
    } catch (e) {
        console.error("Clearing corrupted user data from storage.", e);
        localStorage.removeItem(USER_KEY);
        return null;
    }
}

export function isLoggedIn() {
    return !!getUserInfo();
}

export function checkAuth() {
    if (!isLoggedIn()) {
        alert('You must be logged in to view this page. Redirecting...');
        window.location.href = 'login.html';
    }
}   