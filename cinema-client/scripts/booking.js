import { apiService } from './apiService.js';
import { isLoggedIn, getUserInfo } from './auth.js';

document.addEventListener('DOMContentLoaded', async () => {
    // --- STATE MANAGEMENT ---
    let movieDetails = null;
    let selectedShowtimeId = null;
    let selectedShowtimeInfo = null;
    let selectedSeats = []; 
    
    // Snack State
    let snacksData = [];
    let selectedSnacks = {}; // { 1: 2, 4: 1 } (id: quantity)

    // --- DOM ELEMENTS ---
    const movieTitleEl = document.getElementById('movie-title-booking');
    const showtimesContainer = document.getElementById('showtimes-container');
    const seatingChartContainer = document.getElementById('seating-chart-container');
    const seatingChart = document.getElementById('seating-chart');
    
    // Snack Elements
    const snacksContainer = document.getElementById('snacks-container');
    const snacksGrid = document.getElementById('snacks-grid');

    const summaryContainer = document.getElementById('booking-summary');
    const summaryMovieTitle = document.getElementById('summary-movie-title');
    const summaryShowTime = document.getElementById('summary-show-time');
    const summarySeats = document.getElementById('summary-seats');
    const summarySnacks = document.getElementById('summary-snacks');
    const summaryPrice = document.getElementById('summary-price');
    const confirmBtn = document.getElementById('confirm-booking-btn');

    // --- INITIALIZATION ---
    const movieId = new URLSearchParams(window.location.search).get('id');

    if (!movieId) {
        movieTitleEl.textContent = "Movie Not Found";
        showtimesContainer.innerHTML = '<p class="error-msg">No movie ID provided.</p>';
        return;
    }

    // Load all data
    fetchMovie();
    fetchShowtimes();
    fetchSnacks();

    // --- CORE DATA FUNCTIONS ---

    async function fetchMovie() {
        try {
            const response = await apiService.get(`/movies.php?id=${movieId}`);
            // Handle wrapper { data: { ... } } vs direct { ... }
            const movie = (response.data && response.data.title) ? response.data : response;

            movieDetails = movie;
            movieTitleEl.textContent = movie.title;
            document.title = `Booking: ${movie.title}`;
            summaryMovieTitle.textContent = movie.title;
        } catch (error) {
            console.error("Failed to fetch movie", error);
            movieTitleEl.textContent = "Error Loading Movie";
        }
    }

    async function fetchShowtimes() {
        try {
            const response = await apiService.get(`/showtimes.php?movie_id=${movieId}`);
            
            // FIX: Flatten the grouped data {"2025-12-20": [...], "2025-12-21": [...]}
            const groupedData = response.data || {};
            const allShowtimes = Object.values(groupedData).flat();

            renderShowtimes(allShowtimes);
        } catch (error) {
            console.error("Error fetching showtimes:", error);
            showtimesContainer.innerHTML = '<p>No showtimes available.</p>';
        }
    }

    async function fetchSnacks() {
        try {
            const response = await apiService.get('/snacks.php');
            snacksData = Array.isArray(response) ? response : (response.data || []);
            renderSnacks();
        } catch (error) {
            console.error("Failed to load snacks", error);
            if(snacksGrid) snacksGrid.innerHTML = '<p>Unable to load snacks at this time.</p>';
        }
    }

    // --- RENDER FUNCTIONS ---

    function renderShowtimes(showtimes) {
        if (!showtimes || showtimes.length === 0) {
            showtimesContainer.innerHTML = '<p>No showtimes scheduled.</p>';
            return;
        }

        showtimesContainer.innerHTML = showtimes.map(show => {
            // FIX: Using 'show_time' and 'base_price' from your API
            const date = new Date(show.show_time); 
            const timeString = date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const dateString = date.toLocaleDateString();
            const price = parseFloat(show.base_price || 0);
            
            return `
                <button class="showtime-btn" 
                    data-id="${show.id}" 
                    data-price="${price}" 
                    data-time="${dateString} ${timeString}">
                    ${dateString} <br> <strong>${timeString}</strong> <br>
                    <small>$${price.toFixed(2)}</small>
                </button>
            `;
        }).join('');

        // Add Listeners
        document.querySelectorAll('.showtime-btn').forEach(btn => {
            btn.addEventListener('click', (e) => handleShowtimeSelection(e.currentTarget, btn.dataset));
        });
    }

    function renderSnacks() {
        if (snacksData.length === 0) return;

        snacksGrid.innerHTML = snacksData.map(snack => `
            <div class="snack-card">
                <img src="../assets/images/${snack.image_url}" 
                     onerror="this.onerror=null; this.src='https://placehold.co/200x200?text=${encodeURIComponent(snack.name)}';" 
                     alt="${snack.name}">
                <h4>${snack.name}</h4>
                <p>$${parseFloat(snack.price).toFixed(2)}</p>
                <div class="snack-controls">
                    <button class="snack-btn minus" data-id="${snack.id}">-</button>
                    <span class="snack-qty" id="snack-qty-${snack.id}">0</span>
                    <button class="snack-btn plus" data-id="${snack.id}">+</button>
                </div>
            </div>
        `).join('');

        // Add Snack Listeners
        snacksGrid.querySelectorAll('.snack-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const id = parseInt(e.target.dataset.id);
                const isPlus = e.target.classList.contains('plus');
                updateSnackQuantity(id, isPlus ? 1 : -1);
            });
        });
    }

    // --- INTERACTION HANDLERS ---

    async function handleShowtimeSelection(btnElement, data) {
        // Highlight selection
        document.querySelectorAll('.showtime-btn').forEach(b => b.classList.remove('selected'));
        btnElement.classList.add('selected');

        selectedShowtimeId = data.id;
        selectedShowtimeInfo = {
            price: parseFloat(data.price),
            time: data.time
        };
        
        // Reset selections
        selectedSeats = [];
        
        // Show Sections
        seatingChartContainer.style.display = 'block';
        if(snacksContainer) snacksContainer.style.display = 'block';
        summaryContainer.style.display = 'block';

        // Update Summary Basics
        summaryShowTime.textContent = selectedShowtimeInfo.time;
        updateSummary();

        // Load Seats for this showtime
        await loadSeats(selectedShowtimeId);
    }

    async function loadSeats(showtimeId) {
        seatingChart.innerHTML = '<div class="loading-spinner"></div>';
        try {
            const response = await apiService.get(`/bookings.php?showtime_id=${showtimeId}`);
            const data = Array.isArray(response) ? response : (response.data || []);
            const bookedSeats = data.map(b => b.seat_number);
            renderSeatingChart(bookedSeats);
        } catch (error) {
            console.error("Error loading seats", error);
            seatingChart.innerHTML = '<p>Error loading seating chart.</p>';
        }
    }

    function renderSeatingChart(bookedSeats) {
        seatingChart.innerHTML = '';
        const rows = ['A', 'B', 'C', 'D', 'E', 'F'];
        const seatsPerRow = 8; // FIX: Variable name fixed

        rows.forEach(row => {
            for (let i = 1; i <= seatsPerRow; i++) {
                const seatId = `${row}${i}`;
                const seatEl = document.createElement('div');
                seatEl.classList.add('seat');
                seatEl.dataset.id = seatId;
                seatEl.textContent = seatId;

                if (bookedSeats.includes(seatId)) {
                    seatEl.classList.add('booked');
                } else {
                    seatEl.addEventListener('click', () => toggleSeat(seatEl, seatId));
                }

                seatingChart.appendChild(seatEl);
            }
        });
    }

    function toggleSeat(el, id) {
        if (el.classList.contains('booked')) return;

        if (el.classList.contains('selected')) {
            el.classList.remove('selected');
            selectedSeats = selectedSeats.filter(s => s !== id);
        } else {
            el.classList.add('selected');
            selectedSeats.push(id);
        }
        updateSummary();
    }

    function updateSnackQuantity(id, change) {
        if (!selectedSnacks[id]) selectedSnacks[id] = 0;
        selectedSnacks[id] += change;
        
        if (selectedSnacks[id] < 0) selectedSnacks[id] = 0;

        const qtyDisplay = document.getElementById(`snack-qty-${id}`);
        if (qtyDisplay) qtyDisplay.textContent = selectedSnacks[id];

        updateSummary();
    }

    function updateSummary() {
        // 1. Seats
        const seatCount = selectedSeats.length;
        const seatTotal = seatCount * (selectedShowtimeInfo ? selectedShowtimeInfo.price : 0);
        
        summarySeats.textContent = seatCount > 0 ? selectedSeats.join(', ') : 'None';

        // 2. Snacks
        let snackTotal = 0;
        let snackSummaryText = [];
        
        for (const [id, qty] of Object.entries(selectedSnacks)) {
            if (qty > 0) {
                const snack = snacksData.find(s => s.id == id);
                if (snack) {
                    snackTotal += qty * parseFloat(snack.price);
                    snackSummaryText.push(`${qty}x ${snack.name}`);
                }
            }
        }
        summarySnacks.textContent = snackSummaryText.length > 0 ? snackSummaryText.join(', ') : 'None';

        // 3. Grand Total
        const total = seatTotal + snackTotal;
        summaryPrice.textContent = total.toFixed(2);

        // 4. Button State
        if (seatCount > 0 && isLoggedIn()) {
            confirmBtn.disabled = false;
            confirmBtn.textContent = "Confirm Booking";
        } else if (!isLoggedIn()) {
            confirmBtn.disabled = true;
            confirmBtn.textContent = "Login to Book";
        } else {
            confirmBtn.disabled = true;
            confirmBtn.textContent = "Select Seats";
        }
    }

    // --- SUBMIT BOOKING ---
    confirmBtn.addEventListener('click', async () => {
        if (!selectedShowtimeId || selectedSeats.length === 0) return;

        const user = getUserInfo();
        if (!user) {
            alert("Please login first.");
            window.location.href = '../pages/login.html';
            return;
        }

        confirmBtn.disabled = true;
        confirmBtn.textContent = "Processing...";

        // Construct Snacks Payload
        const snacksPayload = Object.entries(selectedSnacks)
            .filter(([_, qty]) => qty > 0)
            .map(([id, qty]) => ({
                id: parseInt(id),
                quantity: qty,
                price: parseFloat(snacksData.find(s => s.id == id).price)
            }));
            
        // Final Price Calculation
        const seatTotal = selectedSeats.length * selectedShowtimeInfo.price;
        const snackTotal = snacksPayload.reduce((sum, item) => sum + (item.quantity * item.price), 0);
        const finalTotal = seatTotal + snackTotal;

        const bookingData = {
            userId: user.id,
            showtimeId: parseInt(selectedShowtimeId),
            seats: selectedSeats,
            totalPrice: finalTotal,
            snacks: snacksPayload
        };

        try {
            const response = await apiService.post('/bookings.php', bookingData);
            
            if (response.success || response.booking_id) {
                alert("Booking Successful! Enjoy the movie.");
                window.location.href = '../pages/account.html';
            } else {
                throw new Error(response.message || "Unknown error");
            }
        } catch (error) {
            console.error("Booking failed", error);
            const msg = error.response?.data?.message || error.message;
            alert("Booking failed: " + msg);
            confirmBtn.disabled = false;
            confirmBtn.textContent = "Confirm Booking";
        }
    });
});