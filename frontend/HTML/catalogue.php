<?php 
// Connect to the database
$pdo = require_once '../../backend/db.php';

// Get all models
$sql = "SELECT * FROM model";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$models = $stmt->fetchAll(PDO::FETCH_ASSOC); // Now we have all model rows
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
    <div class="logo-title">
      <img src="logo-EMBOSS-nouveau.png" alt="Logo"/>
    </div>
    <nav>
      <a href="home.html">HOME</a>
      <a href="product.html">PRODUCT</a>
      <a href="catalogue.html">CATALOGUE</a>
      <a href="#">PROJECTS</a>
      <a href="contact.html">CONTACT</a>
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
          ?>
          <div class="product-card">
            <div class="product-image"
                 data-link="home.html"
                 style="background-image: url('/backend/image.php?id=<?= $model['idmodel'] ?>');">
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
      <img src="youtube-icon.jpeg" alt="YouTube" />
      <img src="instagram-icon.png" alt="Instagram" />
    </div>
  </footer>

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
      const el = document.querySelector('.font-bastliga');
      const font = window.getComputedStyle(el).getPropertyValue('font-family');
      console.log("Font appliquée :", font);
    };
  </script>

</body>
</html>
