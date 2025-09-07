// frontend/JS_Files/roles.js
const ADMIN_EMAIL = "embossmetalservices@gmail.com";

export function getEmail() {
  return (localStorage.getItem("email") || "").trim().toLowerCase();
}
export function isLoggedIn() {
  return localStorage.getItem("loggedIn") === "true";
}
export function isAdmin() {
  return getEmail() === ADMIN_EMAIL;
}
export function setRoleByEmail(email) {
  const role = (email || "").trim().toLowerCase() === ADMIN_EMAIL ? "admin" : "user";
  localStorage.setItem("role", role);
  return role;
}
export function getRole() {
  return localStorage.getItem("role") || (isAdmin() ? "admin" : "user");
}

/** Enhance header nav: add admin links if admin, plus a small auth box */
export function enhanceHeader() {
  const nav = document.querySelector("header nav");
  if (!nav || nav.dataset.enhanced === "1") return;
  nav.dataset.enhanced = "1";

  if (isAdmin()) {
    const adminLinks = [
      
      { href: "admin/users.html", text: "USERS" },
      { href: "info-contact.php", text: "Mass Emails" },
      { href: "add.php", text: "Ajouter" },
    ];
    adminLinks.forEach(l => {
      const a = document.createElement("a");
      a.href = l.href;
      a.textContent = l.text;
      a.className = "admin-only";
      nav.appendChild(a);
    });
    document.body.classList.add("role-admin");
  } else {
    document.body.classList.add("role-user");
  }

  // Right-side auth box (email + logout)
  const box = document.createElement("div");
  box.className = "auth-box";
  box.innerHTML = `
    <span class="auth-email">${getEmail() || "Guest"}</span>
    <button id="logoutBtn" class="btn-logout">Logout</button>
  `;
  nav.appendChild(box);

  box.querySelector("#logoutBtn").addEventListener("click", () => {
    localStorage.clear();
    window.location.href = "login.html";
  });
}

/** Guard for admin-only pages */
export function requireAdminOrRedirect(to = "../HTML/home.html") {
  if (!(isLoggedIn() && isAdmin())) {
    window.location.replace(to);
  }
}

document.addEventListener("DOMContentLoaded", enhanceHeader);
