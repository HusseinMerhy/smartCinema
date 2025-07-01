import { initializeHeader } from './header.js';
import { apiService } from './apiService.js';

function displayMovieDetails(movie) {
    const container = document.getElementById('movie-details-container');
    const posterUrl = `../${movie.poster_url}`;
    const trailerUrl = movie.trailers.length > 0 ? getEmbedUrl(movie.trailers[0].trailer_url) : '';
    const avgRating = movie.average_rating ? Number(movie.average_rating).toFixed(1) : "N/A";

    let ratingHtml = '';
    let actionButtonHtml = '';

    if (movie.is_coming_soon === '0') {
        ratingHtml = `
            <div class="rating-stars" title="Rating: ${avgRating} / 10">
                ${createStarRating(avgRating)}
                <span style="font-size: 1rem; vertical-align: middle; margin-left: 10px;">
                    (${avgRating})
                </span>
            </div>
        `;
        actionButtonHtml = `
            <a href="booking.html?id=${movie.id}" class="btn">
                <i class='bx bxs-calendar-star'></i> Showtimes & Book
            </a>
        `;
    } else {
        const releaseDate = new Date(movie.release_date).toLocaleDateString(
            'en-US',
            { month: 'long', day: 'numeric', year: 'numeric' }
        );
        actionButtonHtml = `
            <p class="coming-soon-date">
                Coming Soon on ${releaseDate}
            </p>
        `;
    }

    container.innerHTML = `
        <section class="movie-details-hero" style="background-image: url('${posterUrl}')">
            <div class="movie-details-content">
                <div class="movie-poster-details">
                    <img src="${posterUrl}" alt="Poster for ${movie.title}">
                </div>
                <div class="movie-info-details">
                    <h1>${movie.title}</h1>
                    <div class="meta-info">
                        <span class="age-rating">${movie.age_rating || 'N/A'}</span>
                        <span>${new Date(movie.release_date).getFullYear()}</span>
                        •
                        <span>${movie.genre || 'Genre'}</span>
                        •
                        <span>${movie.duration_minutes} min</span>
                    </div>
                    ${ratingHtml}
                    <p>${movie.description || 'No description available.'}</p>
                    <div class="action-buttons">
                        ${actionButtonHtml}
                    </div>
                </div>
            </div>
        </section>
        <section class="movie-more-info">
            <h2 class="section-title">Cast</h2>
            <div class="cast-grid">
                ${
                    movie.cast && movie.cast.length > 0
                        ? movie.cast.map(m =>
                            `<div class="cast-member">
                                <strong>${m.actor_name}</strong>
                                <p>as ${m.character_name}</p>
                            </div>`
                        ).join('')
                        : '<p>Not available.</p>'
                }
            </div>
            ${
                trailerUrl
                    ? `<h2 class="section-title">Trailer</h2>
                       <div class="trailer-container">
                           <iframe src="${trailerUrl}" title="Trailer for ${movie.title}" frameborder="0" allowfullscreen></iframe>
                       </div>`
                    : ''
            }
        </section>
    `;
}

function createStarRating(rating) {
    if (!rating || rating === "N/A") return '';
    const r = Math.round(Number(rating));
    return `
        <span style="color: #ffb400;">
            ${'★'.repeat(r)}
        </span>
        <span style="color: #444;">
            ${'☆'.repeat(10 - r)}
        </span>
    `;
}

function getEmbedUrl(url) {
    if (!url) return '';
    try {
        const v = new URL(url).searchParams.get('v');
        if (v) return `https://www.youtube.com/embed/${v}`;
    } catch (e) {
        return '';
    }
    return '';
}

function displayError(container, message) {
    container.innerHTML = `
        <div style="padding: 80px 20px; text-align: center;">
            <h1 style="font-size: 2rem; color: #ff2c1f;">Error</h1>
            <p style="color: #ccc;">${message}</p>
        </div>
    `;
}

document.addEventListener('DOMContentLoaded', async () => {
    initializeHeader();
    const c = document.getElementById('movie-details-container');
    const id = new URLSearchParams(window.location.search).get('id');
    if (!id) {
        displayError(c, 'No movie selected.');
        return;
    }
    try {
        const r = await apiService.get(`/movies.php?id=${id}`);
        if (r && r.data) {
            document.title = `${r.data.title} - SmartCinema`;
            displayMovieDetails(r.data);
        } else {
            throw new Error(r.message || 'Invalid data from server.');
        }
    } catch (e) {
        displayError(c, e.message);
    }
});
