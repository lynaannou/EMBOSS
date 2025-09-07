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
        <div class ="logo-title" >
            <img src ="logo-EMBOSS-nouveau.png" alt = "Logo"/>
            
        </div>
      <nav>
  <div class="menu-group">
    <a href="home.html">HOME</a>
    <a href="catalogue.php">CATALOGUE</a>
    <a href="#">PROJECTS</a>
    <a href="contact.html">CONTACT</a>
  <div id="auth-container">
  <a href="user.html" class="auth-button" id="signup-button">Sign Up</a>
  <a href="login.html" class="auth-button" id="login-button">Log In</a>
  <img id="profile-pic" class="login-icon" style="display: none;" />
</div>
  
  </div>


</nav>

    </header>

<div class="page-product-container">
  <div class="page-product-left">
   <img src="../../backend/image.php?id=<?= $model['idmodel'] ?>" alt="Image-produit" class="product-image" />
   <h2><?= htmlspecialchars($model['nommodel']) ?></h2>
   <p><?= htmlspecialchars($model['texte']) ?></p>
   <h1><span class="split-word">EMB<br>OSS</span></h1>
  </div>
<div class="page-product-wrapper">
<div class="page-product-slider" id="productSlider">
  <div class="page-product-right">
    <div class="marge-top"><h1>Technicalités</h1></div>
    <div class="product-details">
      <div class="product-info">
        <p>   Formes et textures au service d’un design épuré et élégant.
          Des matériaux et finitions pensés pour sublimer chaque détail !</p>
      </div>
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
        <img src="../../backend/image.php?id=<?= $model['idmodel'] ?>" alt="Image-produit" class="product-image1" id="mainProductImage" />
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
<script src="../JS_Files/auth.js"></script>
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

    // 👇 Ajoute cette condition
    const isWhite = ['white', '#ffffff', 'rgb(255, 255, 255)'].includes(hexColor.toLowerCase());

    if (isWhite) {
      overlay.style.backgroundColor = 'rgba(255, 255, 255, 0.84)';
      overlay.style.mixBlendMode = 'normal'; // ou 'overlay', 'screen' etc.
    } else {
      overlay.style.backgroundColor = hexColor;
      overlay.style.mixBlendMode = 'multiply';
    }
     // ✅ Supprimer la classe active de tous les points
      colorDots.forEach(d => d.classList.remove('active'));

      // ✅ Ajouter la classe active au point cliqué
      dot.classList.add('active');
  });
});

  });
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
