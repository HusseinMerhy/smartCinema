// cinema-client/scripts/utils.js

export const API_BASE_URL = 'http://localhost/smartCinema/cinema-server/api';

// Simple helper to build full URLs
export function apiUrl(path) {
  return `${API_BASE_URL}${path}`;
}