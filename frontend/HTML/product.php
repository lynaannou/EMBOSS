<?php 
// Connexion à la base de données
$pdo = require_once '../../backend/db.php';

// Requête pour récupérer les matières et leurs couleurs associées
$sql = "
SELECT m.nommatiere, c.nomcouleur
FROM matiere m
LEFT JOIN association a ON m.idmatiere = a.idmatiere
LEFT JOIN couleur c ON a.idcouleur = c.idcouleur
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Regrouper les couleurs par matière
$grouped = [];

foreach ($results as $row) {
    $nomMatiere = $row['nommatiere'];
    $nomCouleur = $row['nomcouleur'];

    if (!isset($grouped[$nomMatiere])) {
        $grouped[$nomMatiere] = [];
    }

    if ($nomCouleur) {
        $grouped[$nomMatiere][] = $nomCouleur;
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>EMBOSS MÉTAL SERVICES</title>
  <link rel="stylesheet" href="../Styles/produit.css" />
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
      <h1></h1>
      <p></p>
    </div>
  </section>

  <div class="page-product-container">
    <div class="page-product-left">
      <img src="dune.jpg" alt="Image-produit" class="product-image" />
      <h2>DUNE</h2>
      <p>Découvrez DUNE, la tôle embossée qui allie esthétique et fonctionnalité, pour des créations architecturales d'exception.</p>
    </div>

    <div class="page-product-wrapper">
      <div class="page-product-slider" id="productSlider">

        <!-- PAGE DROITE - TECHNICALITÉS -->
        <div class="page-product-right">
          <div class="marge-top"><h1>Technicalités</h1></div>

          <div class="product-materials">
  <?php foreach ($grouped as $nomMatiere => $couleurs): ?>
    <div class="matiere-item">
      <span class="matiere-name"><?= htmlspecialchars($nomMatiere) ?></span>
      <div class="matiere-couleurs">
        <?php foreach ($couleurs as $nomCouleur): ?>
          <div class="couleur" style="background-color: <?= htmlspecialchars($nomCouleur) ?>;"></div>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>

           

          <div class="marge-bottom">
            <button class="arrow-button" id="arrowButton">
              <img src="arrow1-png.png" alt="arrow" class="arrow-icon" />
            </button>
          </div>
        </div>

        <!-- PAGE PROJETS -->
        <div class="page-product-project">
          <div class="marge-top"><h1>Projets</h1></div>
          <div class="project-content">
            <p>no projets: </p>
          </div>
          <div class="marge-bottom">
            <button class="arrow-button" id="backButton">
              <img src="arrow1-png.png" alt="arrow" class="arrow-icon reverse" />
            </button>
          </div>
        </div>

      </div> 
    </div>
  </div>

  <script>
    const arrowButton = document.getElementById('arrowButton');
    const slider = document.getElementById('productSlider');

    arrowButton.addEventListener('click', () => {
      slider.style.transform = 'translateX(-50vw)';
    });

    const backButton = document.getElementById('backButton');

    backButton.addEventListener('click', () => {
      slider.style.transform = 'translateX(0)';
    });
  </script>

  <footer>
    <p>© 2025 EMBOSS MÉTAL SERVICES. Tous droits réservés.</p>
    <div class="social-icons">
      <img src="facebook-icon.png" alt="Facebook" />
      <img src="linkedin-icon.png" alt="LinkedIn" />
      <img src="youtube-icon.jpeg" alt="YouTube" />
      <img src="instagram-icon.png" alt="Instagram" />
    </div>
  </footer>

</body>
</html>
