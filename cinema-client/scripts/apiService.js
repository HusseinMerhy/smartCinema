// cinema-client/scripts/apiService.js

// ---------------------------------------------------------------------------
// IMPORTANT: CONFIGURATION
// ---------------------------------------------------------------------------
// 1. Check your folder name in htdocs.
// 2. If your folder is 'smartCinema', use the line below.
// 3. If your folder is 'smartCinema-main', change it to 'smartCinema-main'.
// ---------------------------------------------------------------------------

const API_BASE_URL = 'http://localhost/smartCinema/cinema-server/api';

// ---------------------------------------------------------------------------

export const apiService = {
    // We export the URL just in case, but we use the variable above internally
    API_BASE_URL: API_BASE_URL,

    get: async (endpoint) => {
        try {
            // Ensure endpoint starts with /
            const path = endpoint.startsWith('/') ? endpoint : `/${endpoint}`;
            const url = `${API_BASE_URL}${path}`;
            
            const response = await axios.get(url);
            return response.data;
        } catch (error) {
            console.error(`API GET Error: ${endpoint}`, error);
            throw error;
        }
    },

    post: async (endpoint, data) => {
        try {
            const path = endpoint.startsWith('/') ? endpoint : `/${endpoint}`;
            const url = `${API_BASE_URL}${path}`;

            const response = await axios.post(url, data);
            return response.data;
        } catch (error) {
            console.error(`API POST Error: ${endpoint}`, error);
            throw error;
        }
    },
    
    // Helper to handle DELETE requests if needed later
    delete: async (endpoint) => {
        try {
            const path = endpoint.startsWith('/') ? endpoint : `/${endpoint}`;
            const url = `${API_BASE_URL}${path}`;

            const response = await axios.delete(url);
            return response.data;
        } catch (error) {
            console.error(`API DELETE Error: ${endpoint}`, error);
            throw error;
        }
    }
};