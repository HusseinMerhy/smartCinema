// scripts/apiService.js
const API_BASE_URL = '/cinema-server/api';

export const apiService = {
    get: async (endpoint) => {
        try {
            const url = endpoint.startsWith('/') ? `${API_BASE_URL}${endpoint}` : `${API_BASE_URL}/${endpoint}`;
            const response = await axios.get(url);
            return response.data;
        } catch (error) {
            console.error(`API GET Error: ${endpoint}`, error);
            throw error.response ? error.response.data : new Error('Network error or server is not responding.');
        }
    },
    post: async (endpoint, data) => {
        try {
            const url = endpoint.startsWith('/') ? `${API_BASE_URL}${endpoint}` : `${API_BASE_URL}/${endpoint}`;
            const response = await axios.post(url, data);
            return response.data;
        } catch (error) {
            console.error(`API POST Error: ${endpoint}`, error);
            throw error.response ? error.response.data : new Error('Network error or server is not responding.');
        }
    },
    /**
     * --- THE NEW DELETE METHOD ---
     */
    delete: async (endpoint) => {
        try {
            const url = endpoint.startsWith('/') ? `${API_BASE_URL}${endpoint}` : `${API_BASE_URL}/${endpoint}`;
            const response = await axios.delete(url);
            return response.data;
        } catch (error) {
            console.error(`API DELETE Error: ${endpoint}`, error);
            throw error.response ? error.response.data : new Error('Network error or server is not responding.');
        }
    }
};
