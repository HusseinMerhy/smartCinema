import { checkAuth, getUserInfo, login } from './auth.js';
import { initializeHeader } from './header.js';
import { apiService } from './apiService.js';

const ALL_GENRES = ["Action", "Adventure", "Comedy", "Drama", "Sci-Fi", "Thriller", "Horror", "Romance", "Animation", "Biography", "War", "Fantasy", "Crime"];

function populateForm(userData) {
    if (!userData) return;
    const emailEl = document.getElementById('email');
    const usernameEl = document.getElementById('username');
    const phoneEl = document.getElementById('phoneNumber');
    if (emailEl) emailEl.value = userData.email || '';
    if (usernameEl) usernameEl.value = userData.username || '';
    if (phoneEl) phoneEl.value = userData.phoneNumber || '';
    populateGenres(userData.favoriteGenres || []);
}

function populateGenres(savedGenres = []) {
    const container = document.getElementById('genres-container');
    if (!container) return;
    container.innerHTML = ALL_GENRES.map(genre => `<div class="checkbox-group"><input type="checkbox" id="genre-${genre}" name="favoriteGenres" value="${genre}" ${savedGenres.includes(genre) ? 'checked' : ''}><label for="genre-${genre}">${genre}</label></div>`).join('');
}

/**
 * Renders a list of user bookings into the DOM element with the ID 'bookings-container'.
 * 
 * - If no bookings are provided, displays a message indicating there are no bookings.
 * - For each booking, displays movie poster, title, show time, hall, seats, and total price.
 * - Shows a "Cancel Booking" button for upcoming bookings, or marks completed bookings.
 * - Handles missing poster images with a placeholder.
 * - Attaches event listeners to cancel buttons after rendering.
 * 
 * @param {Array<Object>} bookings - Array of booking objects to render. Each object should contain:
 *   @param {string} bookings[].booking_id - Unique identifier for the booking.
 *   @param {string} bookings[].movie_title - Title of the booked movie.
 *   @param {string} bookings[].show_time - ISO date string of the show time.
 *   @param {string} bookings[].hall_name - Name of the cinema hall.
 *   @param {Array<string>} bookings[].seats - List of seat identifiers.
 *   @param {number} bookings[].total_price - Total price of the booking.
 *   @param {string} [bookings[].poster_url] - Optional URL to the movie poster image.
 */
function renderBookings(bookings = []) {
    const container = document.getElementById('bookings-container');
    if (!container) return;
    if (bookings.length === 0) {
        container.innerHTML = '<p>You have no past or upcoming bookings.</p>';
        return;
    }
    container.innerHTML = bookings.map(b => {
        const showTime = new Date(b.show_time);
        const isUpcoming = showTime > new Date();
        const posterUrl = b.poster_url ? `../${b.poster_url}` : 'https://placehold.co/100x150/0a0a0f/fff?text=Poster';
        const cancelButtonHtml = isUpcoming ? `<button class="btn-cancel-booking" data-booking-id="${b.booking_id}">Cancel Booking</button>` : '<span class="booking-status-past">Completed</span>';
        return `
        <div class="booking-card">
            <div class="booking-poster"><img src="${posterUrl}" alt="${b.movie_title}" onerror="this.onerror=null;this.src='https://placehold.co/100x150/0a0a0f/fff?text=Poster';"></div>
            <div class="booking-details">
                <h3>${b.movie_title}</h3>
                <p><strong>When:</strong> ${showTime.toLocaleString('en-US', { dateStyle: 'full', timeStyle: 'short' })}</p>
                <p><strong>Where:</strong> ${b.hall_name}</p>
                <p><strong>Seats:</strong> <span class="seats">${b.seats.join(', ')}</span></p>
                <p><strong>Total Price:</strong> $${b.total_price}</p>
            </div>
            <div class="booking-action">${cancelButtonHtml}</div>
        </div>`;
    }).join('');
    addCancelButtonListeners();
}

async function initializeAccountPage() {
    const loadingSpinner = document.getElementById('account-loading');
    const contentTabs = document.querySelectorAll('.tab-content');
    if(loadingSpinner) loadingSpinner.style.display = 'block';
    contentTabs.forEach(t => t.style.display = 'none');
    try {
        const currentUser = getUserInfo();
        if (!currentUser || !currentUser.id) throw new Error("Session invalid.");
        const [profileRes, bookingsRes] = await Promise.all([
            apiService.get(`/account.php?user_id=${currentUser.id}`),
            apiService.get(`/account.php?user_id=${currentUser.id}&action=getBookings`)
        ]);
        if (profileRes && profileRes.data) populateForm(profileRes.data);
        if (bookingsRes && bookingsRes.data) renderBookings(bookingsRes.data);
    } catch (error) {
        alert(error.message || "Could not load account details.");
    } finally {
        if(loadingSpinner) loadingSpinner.style.display = 'none';
        const activeTab = document.querySelector('.tab-content.active');
        if(activeTab) activeTab.style.display = 'block';
    }
}

function setupTabNavigation() {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');
    tabLinks.forEach(link => {
        link.addEventListener('click', () => {
            tabLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');
            const tabId = link.dataset.tab;
            tabContents.forEach(content => {
                content.style.display = content.id === tabId ? 'block' : 'none';
            });
        });
    });
}

function addCancelButtonListeners() {
    document.querySelectorAll('.btn-cancel-booking').forEach(button => {
        button.addEventListener('click', async (e) => {
            const bookingId = e.target.dataset.bookingId;
            if (confirm("Are you sure you want to cancel this booking? This cannot be undone.")) {
                try {
                    const currentUser = getUserInfo();
                    await apiService.delete(`/bookings.php?booking_id=${bookingId}&user_id=${currentUser.id}`);
                    alert("Booking cancelled successfully.");
                    initializeAccountPage(); 
                } catch (error) {
                    alert(`Failed to cancel booking: ${error.message}`);
                }
            }
        });
    });
}

document.getElementById('account-form').addEventListener('submit', async (event) => {
    event.preventDefault();
    const updateBtn = document.getElementById('update-profile-btn');
    updateBtn.disabled = true;
    updateBtn.textContent = 'Saving...';
    try {
        const currentUser = getUserInfo();
        const newPassword = document.getElementById('newPassword').value;
        if (newPassword && newPassword !== document.getElementById('confirmPassword').value) throw new Error("Passwords do not match.");
        const updatedData = {
            username: document.getElementById('username').value,
            phoneNumber: document.getElementById('phoneNumber').value,
            favoriteGenres: Array.from(document.querySelectorAll('input[name="favoriteGenres"]:checked')).map(cb => cb.value)
        };
        if (newPassword) updatedData.password = newPassword;
        const response = await apiService.post(`/account.php?user_id=${currentUser.id}`, updatedData);
        login(response.data); 
        alert("Profile updated successfully!");
    } catch (error) {
        alert(`Error: ${error.message || 'Could not update profile.'}`);
    } finally {
        updateBtn.disabled = false;
        updateBtn.textContent = 'Update Profile';
    }
});

document.addEventListener('DOMContentLoaded', () => {
    checkAuth();
    initializeHeader();
    setupTabNavigation();
    initializeAccountPage();
});
