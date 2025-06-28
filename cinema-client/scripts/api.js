// FILE: cinema-client/scripts/api.js
// PURPOSE: Centralizes all communication with the backend API.

// IMPORTANT: Adjust this if your PHP server runs on a different port or path.
const API_BASE_URL = 'http://localhost/smartCinema/cinema-server';

/**
 * A robust function for making API calls with Axios.
 * @param {string} endpoint The target endpoint (e.g., '/api/login').
 * @param {string} method The HTTP method ('POST', 'GET').
 * @param {object} [data=null] The data to send for POST requests.
 * @returns {Promise<any>} The JSON data from the server's response.
 */
export async function apiRequest(endpoint, method = 'GET', data = null) {
    const config = {
        method: method,
        url: `${API_BASE_URL}${endpoint}`,
        headers: {
            'Content-Type': 'application/json',
        },
        data: data
    };

    try {
        const response = await axios(config);
        return response.data;
    } catch (error) {
        // If the server sends a specific error message, use it. Otherwise, show a generic one.
        if (error.response && error.response.data && error.response.data.error) {
            throw new Error(error.response.data.error);
        } else {
            console.error('API Error:', error);
            throw new Error('Could not connect to the server. Please try again later.');
        }
    }
}