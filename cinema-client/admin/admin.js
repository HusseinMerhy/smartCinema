import { apiService } from '../scripts/apiService.js';
import { getUserInfo, logout } from '../scripts/auth.js';

// --- STATE MANAGEMENT ---
let allMovies = [];
let allHalls = [];
let allShowtimes = [];

// --- RENDER FUNCTIONS ---
function renderMovies() {
    const container = document.getElementById('movie-list-container');
    if (!container) return;
    if (allMovies.length === 0) { container.innerHTML = '<p>No movies found.</p>'; return; }
    let tableHTML = '<table><thead><tr><th>ID</th><th>Title</th><th>Status</th><th>Actions</th></tr></thead><tbody>';
    allMovies.forEach(movie => {
        tableHTML += `<tr><td>${movie.id}</td><td>${movie.title}</td><td>${movie.is_coming_soon === '1' ? 'Coming Soon' : 'Now Playing'}</td><td><button class="btn-edit btn-edit-movie" data-movie-id="${movie.id}">Edit</button><button class="btn-delete" data-movie-id="${movie.id}">Delete</button></td></tr>`;
    });
    container.innerHTML = tableHTML + '</tbody></table>';
    addMovieActionListeners();
}

function renderShowtimes() {
    const container = document.getElementById('showtime-list-container');
    if (!container) return;
    if (allShowtimes.length === 0) { container.innerHTML = '<p>No showtimes found.</p>'; return; }
    let tableHTML = '<table><thead><tr><th>ID</th><th>Movie</th><th>Hall</th><th>Time</th><th>Actions</th></tr></thead><tbody>';
    allShowtimes.forEach(show => {
        const movieTitle = allMovies.find(m => m.id == show.movie_id)?.title || 'Unknown Movie';
        const hallName = allHalls.find(h => h.id == show.hall_id)?.name || 'Unknown Hall';
        tableHTML += `<tr><td>${show.id}</td><td>${movieTitle}</td><td>${hallName}</td><td>${new Date(show.show_time).toLocaleString()}</td><td><button class="btn-edit btn-edit-showtime" data-showtime-id="${show.id}">Edit</button><button class="btn-delete" data-showtime-id="${show.id}">Delete</button></td></tr>`;
    });
    container.innerHTML = tableHTML + '</tbody></table>';   
    addShowtimeActionListeners();
}

function populateSelect(selectId, items, placeholder) {
    const select = document.getElementById(selectId);
    if (!select) return;
    select.innerHTML = `<option value="">${placeholder}</option>`;
    items.forEach(item => {
        select.innerHTML += `<option value="${item.id}">${item.title || item.name}</option>`;
    });
}

// --- ASYNC DATA FETCHING ---
async function fetchAdminData() {
    const user = getUserInfo();
    try {
        const [nowPlayingRes, comingSoonRes, hallsRes, showtimesRes] = await Promise.all([
            apiService.get(`/movies.php?action=now-playing`),
            apiService.get(`/movies.php?action=coming-soon`),
            apiService.get(`/admin.php?action=getHalls&user_id=${user.id}`),
            apiService.get(`/showtimes.php`)
        ]);

        allMovies = [...(nowPlayingRes.data || []), ...(comingSoonRes.data || [])];
        allHalls = hallsRes.data || [];
        allShowtimes = showtimesRes.data || [];
        
        renderMovies();
        renderShowtimes();
        
        // --- THE FIX IS HERE ---
        // The dropdown should be populated with ALL movies, so it works for editing
        // any showtime, regardless of the movie's status.
        populateSelect('showtime-movie', allMovies, 'Select a movie...');
        populateSelect('showtime-hall', allHalls, 'Select a hall...');

    } catch (e) { 
        console.error("Failed to load admin data", e); 
        document.getElementById('movie-list-container').innerHTML = `<p style="color:red;">Failed to load movie data.</p>`;
        document.getElementById('showtime-list-container').innerHTML = `<p style="color:red;">Failed to load showtime data.</p>`;
    }
}

// --- MOVIE FORM & ACTIONS ---
function handleAddOrUpdateMovieForm() {
    const form = document.getElementById('add-movie-form');
    if (!form) return;
    
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const user = getUserInfo();
        const movieData = {
            title: document.getElementById('title').value,
            poster_url: document.getElementById('poster_url').value,
            genre: document.getElementById('genre').value,
            duration_minutes: document.getElementById('duration_minutes').value,
            age_rating: document.getElementById('age_rating').value,
            description: document.getElementById('description').value,
            release_date: document.getElementById('release_date').value,
            is_coming_soon: document.getElementById('is_coming_soon').checked ? 1 : 0
        };
        const movieIdField = document.getElementById('movie-id');
        const isUpdating = movieIdField.value;

        try {
            if (isUpdating) {
                movieData.movie_id = isUpdating;
                await apiService.post(`/admin.php?action=updateMovie&user_id=${user.id}`, movieData);
                alert('Movie updated successfully!');
            } else {
                await apiService.post(`/admin.php?action=addMovie&user_id=${user.id}`, movieData);
                alert('Movie added successfully!');
            }
            resetMovieForm();
            fetchAdminData();
        } catch (error) { alert(`Failed to save movie: ${error.message}`); }
    });
    
    document.getElementById('cancel-edit-movie-btn').addEventListener('click', resetMovieForm);
}

function resetMovieForm() {
    const form = document.getElementById('add-movie-form');
    document.getElementById('movie-form-title').textContent = 'Add New Movie';
    form.querySelector('.btn-submit').textContent = 'Add Movie';
    document.getElementById('cancel-edit-movie-btn').style.display = 'none';
    document.getElementById('movie-id').value = '';
    form.reset();
}

function addMovieActionListeners() {
    document.querySelectorAll('#movie-list-container .btn-delete').forEach(button => {
        button.addEventListener('click', async (e) => {
            const movieId = e.target.dataset.movieId;
            if (confirm(`DELETE movie ID ${movieId}? This will also delete all associated showtimes and bookings.`)) {
                const user = getUserInfo();
                try {
                    await apiService.delete(`/admin.php?action=deleteMovie&movie_id=${movieId}&user_id=${user.id}`);
                    alert('Movie deleted!');
                    fetchAdminData();
                } catch (error) { alert(`Failed to delete movie: ${error.message}`); }
            }
        });
    });

    document.querySelectorAll('#movie-list-container .btn-edit').forEach(button => {
        button.addEventListener('click', (e) => {
            const movieId = e.target.dataset.movieId;
            const movie = allMovies.find(m => m.id == movieId);
            if (!movie) return;
            
            document.getElementById('movie-form-title').textContent = `Edit Movie ID: ${movieId}`;
            document.getElementById('movie-id').value = movie.id;
            document.getElementById('title').value = movie.title;
            document.getElementById('poster_url').value = movie.poster_url;
            document.getElementById('genre').value = movie.genre;
            document.getElementById('duration_minutes').value = movie.duration_minutes;
            document.getElementById('age_rating').value = movie.age_rating;
            document.getElementById('description').value = movie.description;
            document.getElementById('release_date').value = movie.release_date;
            document.getElementById('is_coming_soon').checked = movie.is_coming_soon === '1';

            document.querySelector('#add-movie-form .btn-submit').textContent = 'Update Movie';
            document.getElementById('cancel-edit-movie-btn').style.display = 'inline-block';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
}

// --- SHOWTIME FORM & ACTIONS ---
function handleAddOrUpdateShowtimeForm() {
    const form = document.getElementById('add-showtime-form');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const user = getUserInfo();
        const showtimeData = {
            movie_id: document.getElementById('showtime-movie').value,
            hall_id: document.getElementById('showtime-hall').value,
            show_time: document.getElementById('show-time').value,
            base_price: document.getElementById('base-price').value,
            format: document.getElementById('format').value,
        };
        const showtimeIdField = document.getElementById('showtime-id');
        const isUpdating = showtimeIdField.value;

        try {
            if (isUpdating) {
                showtimeData.showtime_id = isUpdating;
                await apiService.post(`/admin.php?action=updateShowtime&user_id=${user.id}`, showtimeData);
                alert('Showtime updated!');
            } else {
                await apiService.post(`/admin.php?action=addShowtime&user_id=${user.id}`, showtimeData);
                alert('Showtime added!');
            }
            resetShowtimeForm();
            fetchAdminData();
        } catch (error) { alert(`Failed to save showtime: ${error.message}`); }
    });
    
    document.getElementById('cancel-edit-showtime-btn').addEventListener('click', resetShowtimeForm);
}

function resetShowtimeForm() {
    const form = document.getElementById('add-showtime-form');
    document.getElementById('showtime-form-title').textContent = 'Add New Showtime';
    form.querySelector('.btn-submit').textContent = 'Add Showtime';
    document.getElementById('cancel-edit-showtime-btn').style.display = 'none';
    document.getElementById('showtime-id').value = '';
    form.reset();
}

function addShowtimeActionListeners() {
    document.querySelectorAll('#showtime-list-container .btn-delete').forEach(button => {
        button.addEventListener('click', async (e) => {
            const showtimeId = e.target.dataset.showtimeId;
            if (confirm(`Delete showtime ID ${showtimeId}?`)) {
                const user = getUserInfo();
                try {
                    await apiService.delete(`/admin.php?action=deleteShowtime&showtime_id=${showtimeId}&user_id=${user.id}`);
                    alert('Showtime deleted!');
                    fetchAdminData();
                } catch (error) { alert(`Failed to delete showtime: ${error.message}`); }
            }
        });
    });

    document.querySelectorAll('#showtime-list-container .btn-edit').forEach(button => {
        button.addEventListener('click', (e) => {
            const showtimeId = e.target.dataset.showtimeId;
            const showtime = allShowtimes.find(s => s.id == showtimeId);
            if (!showtime) return;
            
            document.getElementById('showtime-form-title').textContent = `Edit Showtime ID: ${showtimeId}`;
            document.getElementById('showtime-id').value = showtime.id;
            document.getElementById('showtime-movie').value = showtime.movie_id;
            document.getElementById('showtime-hall').value = showtime.hall_id;
            const localDate = new Date(new Date(showtime.show_time).getTime() - (new Date().getTimezoneOffset() * 60000));
            document.getElementById('show-time').value = localDate.toISOString().slice(0, 16);
            document.getElementById('base-price').value = showtime.base_price;
            document.getElementById('format').value = showtime.format;

            document.querySelector('#add-showtime-form .btn-submit').textContent = 'Update Showtime';
            document.getElementById('cancel-edit-showtime-btn').style.display = 'inline-block';
            window.scrollTo({ top: document.getElementById('add-showtime-form').offsetTop, behavior: 'smooth' });
        });
    });
}

// --- GENERAL PAGE SETUP ---
function setupTabs() {
    const nav = document.getElementById('admin-nav');
    if (!nav) return;
    const contents = document.querySelectorAll('.admin-tab-content');
    nav.addEventListener('click', (e) => {
        if (e.target.tagName === 'A' && e.target.dataset.tab) {
            e.preventDefault();
            nav.querySelectorAll('a.admin-tab-link').forEach(a => a.classList.remove('active'));
            e.target.classList.add('active');
            contents.forEach(c => {
                c.classList.toggle('active', c.id === e.target.dataset.tab);
            });
        }
    });
}

function setupLogout() {
    const logoutBtn = document.getElementById('logout-btn-admin');
    if(logoutBtn) { logoutBtn.addEventListener('click', (e) => { e.preventDefault(); logout(); }); }
}

function protectAdminPage() { 
    const user = getUserInfo(); 
    if (!user || user.role !== 'admin') { 
        alert('Access Denied. You must be an administrator to view this page.'); 
        window.location.href = '../index.html'; 
    } 
}

// --- INITIALIZATION ---
document.addEventListener('DOMContentLoaded', () => {
    protectAdminPage();
    setupLogout();
    setupTabs();
    fetchAdminData();
    handleAddOrUpdateMovieForm();
    handleAddOrUpdateShowtimeForm();
});
