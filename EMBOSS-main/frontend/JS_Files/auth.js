(function () {
function renderAuthUI() {
const auth = document.getElementById('auth-container');
if (!auth) return; // header not on this page


const isLoggedIn = localStorage.getItem('loggedIn') === 'true';
const profileImage = localStorage.getItem('profileImage') || 'avatar1.jpeg';


if (!isLoggedIn) {
auth.innerHTML = `
<a href="user.html" class="auth-button" id="signup-button">Sign Up</a>
<a href="login.html" class="auth-button" id="login-button">Log In</a>
<img id="profile-pic" class="login-icon" style="display:none" />`;
return;
}


auth.innerHTML = '';
const img = document.createElement('img');
img.id = 'profile-pic';
img.className = 'login-icon';
img.alt = 'Profil utilisateur';
img.src = profileImage;
img.style.cursor = 'pointer';
img.addEventListener('click', () => {
window.location.href = 'profile.html';
});
auth.appendChild(img);
}


// Lightweight global logout helper
window.logout = function () {
localStorage.removeItem('loggedIn');
localStorage.removeItem('profileImage');
localStorage.removeItem('email');
localStorage.removeItem('username');
localStorage.removeItem('loginProvider');
window.location.href = 'home.html';
};


document.addEventListener('DOMContentLoaded', renderAuthUI);
})();