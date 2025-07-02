import { getUserInfo, logout } from './auth.js';

export function initializeHeader() {
    const loginBtn = document.getElementById('login-btn');
    const userMenu = document.getElementById('user-menu');
    const userIcon = document.getElementById('user-icon');
    const userDropdown = document.getElementById('user-dropdown');
    const logoutBtn = document.getElementById('logout-btn');

    if (!loginBtn || !userMenu || !userIcon || !userDropdown || !logoutBtn) {
        return;
    }

    const user = getUserInfo();

    if (user) {
        // User is LOGGED IN
        loginBtn.style.display = 'none';
        userMenu.style.display = 'block';
        
        // --- THE FIX IS HERE ---
        const existingAdminLink = userDropdown.querySelector('.admin-link');
        if (user.role === 'admin' && !existingAdminLink) {
            const adminLink = document.createElement('a');
            // Use an absolute path from the web root to always be correct
            adminLink.href = '/smartCinema/cinema-client/admin/index.html';
            adminLink.textContent = 'Admin Panel';
            adminLink.className = 'admin-link';
            userDropdown.prepend(adminLink);
        } else if (user.role !== 'admin' && existingAdminLink) {
            existingAdminLink.remove();
        }
        
        if (!userIcon.dataset.listenerAttached) {
            userIcon.addEventListener('click', (event) => {
                event.stopPropagation();
                userDropdown.classList.toggle('show');
            });
            logoutBtn.addEventListener('click', (e) => {
                e.preventDefault();
                logout();
            });
            userIcon.dataset.listenerAttached = 'true';
        }

    } else {
        // User is LOGGED OUT
        loginBtn.style.display = 'block';
        userMenu.style.display = 'none';
    }

    window.addEventListener('click', () => {
        if (userDropdown && userDropdown.classList.contains('show')) {
            userDropdown.classList.remove('show');
        }
    });
}
