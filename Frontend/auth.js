/* =====================
   Auth state manager:
   runs on every page 
   reads localStorage to decide if to show "Log in" or "Profile" in the header
   ===================== */

(function () {

    /* ---------- helpers ---------- */

    function isLoggedIn() {
        return localStorage.getItem('sj_logged_in') === 'true';
    }

    function login() {
        localStorage.setItem('sj_logged_in', 'true');
    }

    function logout() {
        localStorage.removeItem('sj_logged_in');
    }

    /* ---------- update header button ---------- */

    function updateHeader() {
        const loginBtn = document.getElementById('header-login-btn');
        if (!loginBtn) return;

        if (isLoggedIn()) {
            loginBtn.textContent = 'Profile';
            loginBtn.href = 'profile.html';
        } else {
            loginBtn.textContent = 'Log in';
            loginBtn.href = 'login.html';
        }
    }

    /* ---------- login form ---------- */

    function bindLoginForm() {
        const loginSubmit = document.getElementById('login-submit');
        if (!loginSubmit) return;

        loginSubmit.addEventListener('click', function (e) {
            e.preventDefault();
            login();
            window.location.href = 'index.html';
        });
    }

    /* ---------- register form ---------- */

    function bindRegisterForm() {
        const registerSubmit = document.getElementById('register-submit');
        if (!registerSubmit) return;

        registerSubmit.addEventListener('click', function (e) {
            e.preventDefault();
            login();
            window.location.href = 'index.html';
        });
    }

    /* ---------- logout button (profile page, optional) ---------- */

    function bindLogout() {
        const logoutBtn = document.getElementById('logout-btn');
        if (!logoutBtn) return;

        logoutBtn.addEventListener('click', function (e) {
            e.preventDefault();
            logout();
            window.location.href = 'index.html';
        });
    }

    /* ---------- init ---------- */

    document.addEventListener('DOMContentLoaded', function () {
        updateHeader();
        bindLoginForm();
        bindRegisterForm();
        bindLogout();
    });

})();
