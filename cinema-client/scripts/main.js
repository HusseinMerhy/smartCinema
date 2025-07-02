import { initializeHeader } from './header.js';
import { apiService } from './apiService.js';

function createOpeningMovieBox(movie) {
    const boxWrapper = document.createElement('div');
    boxWrapper.classList.add('box');

    const poster = movie.poster_url || 'https://placehold.co/320x480?text=No+Image';
    
    boxWrapper.innerHTML = `
        <a href="pages/movie.html?id=${movie.id}" class="box-img-link">
            <div class="box-img">
                <img src="${poster}" alt="${movie.title}" loading="lazy">
            </div>
        </a>
        <div class="movie-info">
            <h3>${movie.title}</h3>
            <span>${movie.duration_minutes} min | ${movie.genre || 'N/A'}</span>
            <a href="pages/booking.html?id=${movie.id}" class="card-showtimes-btn">Showtimes</a>
        </div>`;
    return boxWrapper;
}

function createComingSoonMovie(movie) {
    const b = document.createElement('a');
    b.href = `pages/movie.html?id=${movie.id}`;
    b.className = 'box';
    const p = movie.poster_url || 'https://placehold.co/200x300?text=No+Image';
    b.innerHTML = `
        <div class="box-img">
            <img src="${p}" alt="${movie.title}" loading="lazy">
        </div>
        <h3>${movie.title}</h3>
    `;
    return b;
}

async function fetchAndPopulate(endpoint, container, creationFunction) {
    try {
        const r = await apiService.get(endpoint);
        container.innerHTML = '';
        if (r && Array.isArray(r.data)) {
            if (r.data.length > 0) {
                r.data.forEach(m => container.appendChild(creationFunction(m)));
            } else {
                container.innerHTML = '<p>No movies to display.</p>';
            }
        } else {
            container.innerHTML = '<p>Could not find movie data.</p>';
        }
    } catch (e) {
        container.innerHTML = `<p style="color:red;">Could not load movies.</p>`;
    }
}

function initializeCustomSlider() {
    const n = document.getElementById('next-slide-btn'),
        p = document.getElementById('prev-slide-btn'),
        c = document.getElementById('coming-soon-wrapper');
    if (n && p && c) {
        const u = () => {
            p.style.display = c.scrollLeft <= 0 ? 'none' : 'grid';
            const i = c.scrollLeft + c.clientWidth >= c.scrollWidth - 1;
            n.style.display = i ? 'none' : 'grid';
        };
        n.addEventListener('click', () => {
            c.scrollBy({ left: c.clientWidth * 0.8, behavior: 'smooth' });
        });
        p.addEventListener('click', () => {
            c.scrollBy({ left: -(c.clientWidth * 0.8), behavior: 'smooth' });
        });
        c.addEventListener('scroll', u);
        window.addEventListener('resize', u);
        setTimeout(u, 100);
    }
}

function initializeMobileMenu() {
    const i = document.getElementById('menu-icon'),
        n = document.querySelector('.navbar');
    if (i && n) {
        i.addEventListener('click', () => {
            n.classList.toggle('active');
            i.classList.toggle('bx-x');
        });
    }
}

function initializeHeroButtons() {
    document.querySelectorAll('#home .swiper-slide').forEach(slide => {
        const button = slide.querySelector('.book-now-btn');
        const movieId = slide.dataset.movieId;
        if (button && movieId) {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                window.location.href = `pages/booking.html?id=${movieId}`;
            });
        }
    });
}

document.addEventListener('DOMContentLoaded', async () => {
    initializeHeader();
    initializeMobileMenu();
    if (typeof Swiper !== 'undefined') {
        new Swiper('.home.swiper', {
            loop: true,
            autoplay: {
                delay: 4000,
                disableOnInteraction: false
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true
            }
        });
    }
    initializeHeroButtons();
    const moviesContainer = document.getElementById('movies-container');
    const comingSoonWrapper = document.getElementById('coming-soon-wrapper');
    await fetchAndPopulate('/movies.php?action=now-playing', moviesContainer, createOpeningMovieBox);
    await fetchAndPopulate('/movies.php?action=coming-soon', comingSoonWrapper, createComingSoonMovie);
    initializeCustomSlider();
});