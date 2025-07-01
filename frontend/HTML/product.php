<?php
$pdo = require_once '../../backend/db.php';

$idmodel = $_GET['model'] ?? null;
if (!$idmodel) {
    die("Aucun modèle spécifié.");
}

// On récupère les infos du modèle
$sql_model = "SELECT * FROM model WHERE idmodel = ?";
$stmt_model = $pdo->prepare($sql_model);
$stmt_model->execute([$idmodel]);
$model = $stmt_model->fetch(PDO::FETCH_ASSOC);

if (!$model) {
    die("Modèle introuvable.");
}

// On récupère toutes les associations matière/couleur pour ce modèle
$sql_variantes = "
SELECT ma.nommatiere, c.nomcouleur
FROM produit p
JOIN association a ON p.idassociation = a.idassociation
JOIN matiere ma ON a.idmatiere = ma.idmatiere
JOIN couleur c ON a.idcouleur = c.idcouleur
WHERE p.idmodel = ?
";
$stmt_variantes = $pdo->prepare($sql_variantes);
$stmt_variantes->execute([$idmodel]);
$raw_variantes = $stmt_variantes->fetchAll(PDO::FETCH_ASSOC);

// Regroupement par matière
$variantes = [];
foreach ($raw_variantes as $row) {
    $matiere = $row['nommatiere'];
    $couleur = $row['nomcouleur'];

    if (!isset($variantes[$matiere])) {
        $variantes[$matiere] = [];
    }
    $variantes[$matiere][] = $couleur;
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
      <a href="product.php">PRODUCT</a>
      <a href="catalogue.php">CATALOGUE</a>
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
  <img src="/backend/image.php?id=<?= $model['idmodel'] ?>" alt="Image-produit" class="product-image" />
  <h2><?= htmlspecialchars($model['nommodel']) ?></h2>
  <p><?= htmlspecialchars($model['texte']) ?></p>
</div>

<div class="page-product-right">
  <div class="marge-top"><h1>Technicalités</h1></div>
  <div class="product-materials">
    <?php foreach ($variantes as $matiere => $couleurs): ?>
  <div class="matiere-item">
    <span class="matiere-name"><?= htmlspecialchars($matiere) ?></span>
    <div class="matiere-couleurs">
  <?php foreach ($couleurs as $couleur): ?>
    <div 
      class="couleur" 
      style="background-color: <?= htmlspecialchars($couleur) ?>;" 
      data-color="<?= htmlspecialchars($couleur) ?>">
    </div>
  <?php endforeach; ?>
</div>

    
  </div>
  
<?php endforeach; ?>

  </div>
<div class="image-wrapper">
  <img src="/backend/image.php?id=<?= $model['idmodel'] ?>" alt="Image-produit" class="product-image1" id="mainProductImage" />
  <div class="image-overlay" id="imageOverlay"></div>
</div>


</div>
<div class="marge-bottom">
            <button class="arrow-button" id="arrowButton">
              <img src="arrow1-png.png" alt="arrow" class="arrow-icon" />
            </button>
          </div>
        </div>

       
        <div class="page-product-project">
          <div class="marge-top"><h1>Projets</h1></div>
          <div class="project-content">
            
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
  <footer>
    <p>© 2025 EMBOSS MÉTAL SERVICES. Tous droits réservés.</p>
    <div class="social-icons">
      <img src="facebook-icon.png" alt="Facebook" />
      <img src="linkedin-icon.png" alt="LinkedIn" />
      <img src="youtube-icon.png" alt="YouTube" />
      <img src="instagram-icon.png" alt="Instagram" />
    </div>
  </footer>
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

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const colorDots = document.querySelectorAll('.couleur');
    const mainImage = document.getElementById('mainProductImage');
    const overlay = document.getElementById('imageOverlay');
    const modelId = <?= json_encode($model['idmodel']) ?>;

    if (!mainImage || !overlay) {
      console.error("mainProductImage ou imageOverlay introuvable !");
      return;
    }

    colorDots.forEach(dot => {
      dot.addEventListener('click', () => {
        const hexColor = dot.style.backgroundColor;
        overlay.style.backgroundColor = hexColor;

        const imageUrl = `/backend/image.php?id=${modelId}`;
        mainImage.style.opacity = 0.5;
        setTimeout(() => {
          mainImage.src = imageUrl;
          mainImage.onload = () => {
            mainImage.style.opacity = 1;
          };
        }, 200);
      });
    });
  });
</script>

</body>

</html>
