<?php 

// Connexion à la base de données
$pdo = require_once '../../backend/db.php';

// Requête pour récupérer tous les modèles
$sql = "
SELECT DISTINCT m.idmodel, m.nommodel
FROM model m
JOIN produit p ON p.idmodel = m.idmodel
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$models = $stmt->fetchAll(PDO::FETCH_ASSOC); // tu définis bien $models ici
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Catalogue - EMBOSS MÉTAL SERVICES</title>
  <link rel="stylesheet" href="../Styles/catalogue.css" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Italiana&family=Numans&display=swap" rel="stylesheet">
</head>
<body>
  
    <header>
        <div class ="logo-title" >
            <img src ="logo-EMBOSS-nouveau.png" alt = "Logo"/>
            
        </div>
      <nav>
  <div class="menu-group">
    <a href="home.html">HOME</a>
    <a href="catalogue.php">CATALOGUE</a>
    <a href="contact.html">CONTACT</a>
  <div id="auth-container">
  <a href="user.php" class="auth-button" id="signup-button">Sign Up</a>
  <a href="login.html" class="auth-button" id="login-button">Log In</a>
  <img id="profile-pic" class="login-icon" style="display: none;" />
</div>
  </div>
  

</nav>
</header>

  <section class="hero">
    <img src="Hero Image (1).jpg" alt="Hero Image" class="hero-image" />
    <div class="hero-text">
      <h1>CATALOGUE</h1>
      <p>Ce catalogue présente notre gamme de tôles embossées, classées par familles de motifs. Chaque design est disponible en plusieurs finitions, épaisseurs et dimensions.</p>
    </div>
  </section>

  <section class="carousel-section">
    <div class="carousel-wrapper">
      <button class="arrow left">&#10094;</button>
      <div class="carousel-container">
        <?php
        $fonts = [
          'WATER RIPLE' => 'font-water',
          'DUNE' => 'font-dune',
          'SIGNATURE' => 'font-signature',
          'QUILTED' => 'font-quilted',
          'LINE' => 'font-line'
        ];
        ?>
<?php foreach ($models as $model): ?>
  <?php
    $nom = $model['nommodel'];
    $font = $fonts[$nom] ?? 'font-italiana';
    $idmodel = $model['idmodel'];
  ?>
<div class="product-card" data-link="product.php?model=<?= $model['idmodel'] ?>">
  <div class="product-image"
    style="background-image: url('../../backend/image.php?id=<?= $model['idmodel'] ?>');">
  </div>


    <p class="product-title <?= $font ?>"><?= htmlspecialchars($nom) ?></p>
  </div>
<?php endforeach; ?>



      </div>
      <button class="arrow right">&#10095;</button>
    </div>
  </section>

  <footer>
    <p>© 2025 EMBOSS MÉTAL SERVICES. Tous droits réservés.</p>
    <div class="social-icons">
      <img src="facebook-icon.png" alt="Facebook" />
      <img src="linkedin-icon.png" alt="LinkedIn" />
      <img src="youtube-icon.png" alt="YouTube" />
      <img src="instagram-icon.png" alt="Instagram" />
    </div>
  </footer>
<script src="../JS_Files/auth.js"></script>
  <script>
    const container = document.querySelector('.carousel-container');
    const cards = document.querySelectorAll('.product-card');
    const leftArrow = document.querySelector('.arrow.left');
    const rightArrow = document.querySelector('.arrow.right');

    let currentIndex = 0;

    function updateFocus() {
      cards.forEach(card => card.classList.remove('active'));
      const activeCard = cards[currentIndex];
      activeCard.classList.add('active');

      activeCard.scrollIntoView({
        behavior: 'smooth',
        inline: 'center',
        block: 'nearest'
      });
    }

    leftArrow.addEventListener('click', () => {
      currentIndex = (currentIndex - 1 + cards.length) % cards.length;
      updateFocus();
    });

    rightArrow.addEventListener('click', () => {
      currentIndex = (currentIndex + 1) % cards.length;
      updateFocus();
    });

    updateFocus();
  </script>

  <script>
    document.querySelectorAll('.product-card').forEach(card => {
      card.addEventListener('click', () => {
        const link = card.getAttribute('data-link');
        if (link) {
          window.location.href = link;
        }
      });
    });
  </script>

  <script>
    window.onload = () => {
      const el = document.querySelector('.font-signature');
      const font = window.getComputedStyle(el).getPropertyValue('font-family');
      console.log("Font appliquée :", font);
    };
  </script>
<div id="login-popup">
  <div class="popup-overlay"></div>
  <div class="popup-box">
    <h2>Accès restreint</h2>
    <p>Veuillez vous connecter ou vous inscrire pour accéder au catalogue.</p>
    <div class="popup-buttons">
      <a href="login.html">Connexion</a>
      <a href="user.html">Inscription</a>
      <a href="home.html" class="return-home">Retour à l’accueil</a>
    </div>
  </div>
</div>

<script>
  const isLoggedIn = localStorage.getItem('loggedIn') === 'true';
  const profileImageUrl = localStorage.getItem('profileImage');
  const authContainer = document.getElementById('auth-container');

  // Affichage de l'icône si connecté
  if (isLoggedIn && profileImageUrl) {
    authContainer.innerHTML = '';

    const icon = document.createElement('img');
    icon.classList.add('login-icon');
    icon.src = profileImageUrl;
    icon.alt = 'Profil utilisateur';

    authContainer.appendChild(icon);
  }

  // Contrôle de la popup
  window.addEventListener("scroll", () => {
    if (!isLoggedIn && !window.popupShown && window.scrollY + window.innerHeight >= document.body.scrollHeight - 100) {
      const popup = document.getElementById("login-popup");
      if (popup) popup.style.display = "block";
      window.popupShown = true;
    }
  });

  // Si déjà connecté, on supprime carrément la popup du DOM
  if (isLoggedIn) {
    document.addEventListener("DOMContentLoaded", () => {
      const popup = document.getElementById("login-popup");
      if (popup) popup.remove();
    });
  }
</script>

<script>
  const isLoggedIn = localStorage.getItem('loggedIn') === 'true';
  const profileImageUrl = localStorage.getItem('profileImage');

  
  const authContainer = document.getElementById('auth-container');

  if (isLoggedIn && profileImageUrl) {
    authContainer.innerHTML = '';

    const icon = document.createElement('img');
    icon.classList.add('login-icon');
    icon.src = profileImageUrl;
    icon.alt = 'Profil utilisateur';

    authContainer.appendChild(icon);
  }
</script>
<!-- at the bottom of every page that has the header -->
<script type="module">
  import { enhanceHeader } from "../JS_Files/roles.js";
  // runs on DOMContentLoaded inside roles.js automatically;
  // importing once per page is enough.
</script>

</body>
</html>
