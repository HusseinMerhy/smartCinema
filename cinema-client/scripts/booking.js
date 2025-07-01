// smartCinema/cinema-client/scripts/booking.js

import { apiService } from './apiService.js';
import { isLoggedIn, getUserInfo } from './auth.js';

document.addEventListener('DOMContentLoaded', async () => {
    // --- STATE MANAGEMENT ---
    // This object holds all the dynamic information for the page.
    let movieDetails = null;
    let selectedShowtimeId = null;
    let selectedShowtimeInfo = null; // Will store layout, price, etc.
    let selectedSeats = []; // An array to hold seat IDs like ['A1', 'A2']

    // --- DOM ELEMENTS ---
    // Caching all necessary DOM elements for quick access.
    const movieTitleEl = document.getElementById('movie-title-booking');
    const showtimesContainer = document.getElementById('showtimes-container');
    const seatingChartContainer = document.getElementById('seating-chart-container');
    const seatingChart = document.getElementById('seating-chart');
    const summaryContainer = document.getElementById('booking-summary');
    const confirmBtn = document.getElementById('confirm-booking-btn');

    // --- INITIALIZATION ---
    // Get the movie ID from the URL to know what to load.
    const movieId = new URLSearchParams(window.location.search).get('id');

    if (!movieId) {
        movieTitleEl.textContent = "Movie Not Found";
        showtimesContainer.innerHTML = '<p style="color: red;">No movie ID was provided in the URL. Please go back and select a movie.</p>';
        return;
    }

    // --- CORE LOGIC FUNCTIONS ---

    /**
     * Fetches the main movie details to display the title.
     */
    async function fetchMovie() {
        try {
            const response = await apiService.get(`/movies.php?id=${movieId}`);
            movieDetails = response.data;
            movieTitleEl.textContent = movieDetails.title;
            document.title = `Booking for ${movieDetails.title} - SmartCinema`;
            document.getElementById('summary-movie-title').textContent = movieDetails.title;
        } catch (error) {
            movieTitleEl.textContent = "Error Loading Movie";
            console.error("Failed to fetch movie details:", error);
        }
    }

    /**
     * Fetches all available showtimes for the current movie.
     */
    async function fetchShowtimes() {
        try {
            const response = await apiService.get(`/showtimes.php?movie_id=${movieId}`);
            renderShowtimes(response.data);
        } catch (error) {
            showtimesContainer.innerHTML = '<p style="color: red;">Could not load showtimes for this movie.</p>';
            console.error("Failed to fetch showtimes:", error);
        }
    }
    
    /**
     * Renders the fetched showtimes, grouped by date.
     * @param {object} showtimeGroups - An object where keys are dates (e.g., "2024-06-30").
     */
    function renderShowtimes(showtimeGroups) {
        if (Object.keys(showtimeGroups).length === 0) {
            showtimesContainer.innerHTML = '<p>Sorry, there are no upcoming showtimes for this movie.</p>';
            return;
        }

        let html = '';
        for (const date in showtimeGroups) {
            const day = new Date(date).toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' });
            html += `<div class="showtime-group"><h3>${day}</h3><div class="times-grid">`;
            showtimeGroups[date].forEach(show => {
                const time = new Date(show.show_time).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
                html += `<div class="time-slot" data-showtime-id="${show.id}" data-show-time="${day}, ${time}" data-price="${show.base_price}">
                            ${time}
                            <span>${show.format} - ${show.hall_name}</span>
                         </div>`;
            });
            html += `</div></div>`;
        }
        showtimesContainer.innerHTML = html;
        addShowtimeClickListeners();
    }

    /**
     * Adds click event handlers to each rendered showtime slot.
     */
    function addShowtimeClickListeners() {
        document.querySelectorAll('.time-slot').forEach(slot => {
            slot.addEventListener('click', handleShowtimeSelection);
        });
    }

    /**
     * Handles what happens when a user clicks a showtime.
     * It fetches and displays the seating chart for that specific show.
     */
    async function handleShowtimeSelection(event) {
        resetSeating();
        document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('active'));
        
        const selectedSlot = event.currentTarget;
        selectedSlot.classList.add('active');
        
        selectedShowtimeId = selectedSlot.dataset.showtimeId;
        const price = selectedSlot.dataset.price;
        const time = selectedSlot.dataset.showTime;
        
        seatingChart.innerHTML = '<div class="loading-spinner"></div>';
        seatingChartContainer.style.display = 'block';
        summaryContainer.style.display = 'block';
        
        document.getElementById('summary-show-time').textContent = time;

        try {
            const response = await apiService.get(`/showtimes.php?showtime_id=${selectedShowtimeId}`);
            selectedShowtimeInfo = { ...response.data, price }; // Combine layout, booked seats, and price
            renderSeatingChart(selectedShowtimeInfo);
        } catch (error) {
            seatingChart.innerHTML = '<p style="color: red;">Could not load seating chart.</p>';
            console.error(error);
        }
    }
    
    /**
     * Generates and displays the seating chart grid UI.
     * @param {object} details - Contains layout and booked_seats array.
     */
    function renderSeatingChart({ layout, booked_seats }) {
        if (!layout) {
            seatingChart.innerHTML = '<p>Seating information is currently unavailable.</p>';
            return;
        }
        seatingChart.innerHTML = '';
        for (let i = 0; i < layout.total_rows; i++) {
            const rowEl = document.createElement('div');
            rowEl.className = 'seat-row';
            const rowLetter = String.fromCharCode(65 + i); // A, B, C...

            for (let j = 1; j <= layout.seats_per_row; j++) {
                const seatEl = document.createElement('div');
                const seatId = `${rowLetter}${j}`;
                seatEl.className = 'seat';
                seatEl.dataset.seatId = seatId;

                if (booked_seats.includes(seatId)) {
                    seatEl.classList.add('booked');
                } else {
                    seatEl.addEventListener('click', handleSeatClick);
                }
                rowEl.appendChild(seatEl);
            }
            seatingChart.appendChild(rowEl);
        }
    }
    
    /**
     * Handles a user clicking on an available seat to select or deselect it.
     */
    function handleSeatClick(event) {
        const seat = event.currentTarget;
        const seatId = seat.dataset.seatId;

        // Toggle selection
        if (seat.classList.contains('selected')) {
            seat.classList.remove('selected');
            selectedSeats = selectedSeats.filter(s => s !== seatId); // Remove from array
        } else {
            seat.classList.add('selected');
            selectedSeats.push(seatId); // Add to array
        }
        updateSummary();
    }

    /**
     * Updates the booking summary section with selected seats and total price.
     */
    function updateSummary() {
        const seatCount = selectedSeats.length;
        if (seatCount > 0) {
            const price = selectedShowtimeInfo.price;
            const totalPrice = (seatCount * price).toFixed(2);
            document.getElementById('summary-seats').textContent = selectedSeats.sort().join(', ');
            document.getElementById('summary-price').textContent = totalPrice;
            confirmBtn.disabled = false; // Enable the confirm button
        } else {
            resetSummary(); // If no seats are selected, reset the summary
        }
    }

    // --- RESET AND UTILITY FUNCTIONS ---

    function resetSummary() {
        document.getElementById('summary-seats').textContent = 'None';
        document.getElementById('summary-price').textContent = '0.00';
        confirmBtn.disabled = true;
    }

    function resetSeating() {
        seatingChartContainer.style.display = 'none';
        summaryContainer.style.display = 'none';
        selectedSeats = [];
        resetSummary();
    }
    
    // --- FINAL ACTION: CONFIRM BOOKING ---

    confirmBtn.addEventListener('click', async () => {
        const userInfo = getUserInfo();

        if (!isLoggedIn() || !userInfo || typeof userInfo.id === 'undefined') {
            alert('Your session is invalid or has expired. Please log out and log back in to book tickets.');
            return;
        }

        const bookingData = {
            userId: userInfo.id,
            showtimeId: selectedShowtimeId,
            seats: selectedSeats,
            totalPrice: parseFloat(document.getElementById('summary-price').textContent)
        };

        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Processing...';

        try {
            const response = await apiService.post('/bookings.php', bookingData);
            alert(response.message || 'Your booking is confirmed!');
            window.location.href = 'account.html'; // Redirect to account page on success

        } catch (error) {
            console.error('Booking failed:', error);
            alert(`Booking failed: ${error.message || 'An unknown error occurred. Please try again.'}`);
            
            // If the error was a seat conflict, refresh the seating chart to show the newly booked seat
            if (error.message && error.message.includes('seat')) {
                const updatedDetails = await apiService.get(`/showtimes.php?showtime_id=${selectedShowtimeId}`);
                renderSeatingChart(updatedDetails.data);
                // Reset user's selection
                selectedSeats = [];
                updateSummary();
            }

            confirmBtn.disabled = false;
            confirmBtn.textContent = 'Confirm Booking';
        }
    });

    // --- START THE PROCESS WHEN PAGE LOADS ---
    await fetchMovie();
    await fetchShowtimes();
});