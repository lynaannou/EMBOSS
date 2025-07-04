<?php 
// Connexion à la base de données
$pdo = require_once '../../backend/db.php';

// Requête pour récupérer tous les modèles
$sql = "
SELECT idmatiere, nommatiere
FROM matiere;
";


$stmt = $pdo->prepare($sql);
$stmt->execute();
$models = $stmt->fetchAll(PDO::FETCH_ASSOC); // tu définis bien $models ici
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>EMBOSS MÉTAL SERVICES</title>
  <link rel="stylesheet" href="../Styles/add.css" />
</head>
<body>
     <header>
        <div class ="logo-title" >
            <img src ="logo-EMBOSS-nouveau.png" alt = "Logo"/>
            
        </div>
        <nav>
      <a href="home.html">HOME</a>
      <a href="product.html">PRODUCT</a>
      <a href="catalogue.html">CATALOGUE</a>
      <a href="#">PROJECTS</a>
      <a href="contact.html">CONTACT</a>
    </nav>
    </header>
<div class="add-card">
  <h1 class="card-title">Ajouter un Model</h1>

  <div class="add-content">
<form id="add-product-form" method="POST" enctype="multipart/form-data">


      <label for="product-name">Nom du model</label>
      <input type="text" id="product-name" name="product_name" required />

      <label for="product-description">Description</label>
      <textarea id="product-description" name="product_description" rows="5" required></textarea>
      <input type="file" id="photo-input" name="photo" accept="image/*" hidden />

      <button type="submit">Ajouter</button>
    </form>

    <div class="add-photo" id="photo-drop-area">
      <span class="plus-icon">+</span>
      <input type="file" id="photo-input" name="photo" accept="image/*" hidden />
    </div>
  </div>
</div>
<div class="add-card">
  <h1 class="card-title">Ajouter une Matière</h1>

  <div class="add-content">
    <!-- Left section: material form -->
    <form id="add-material-form" class="material-form" method="POST" action="/backend/save-material.php">
      <label for="material-name">Nom de la matière</label>
      <input type="text" id="material-name" name="material_name" />
    <input type="hidden" id="colors-input-matiere" name="colors" value="[]">
      <div id="materialList" class="palette"></div>

      <button type="submit">Ajouter la matière</button>
    </form>

    <!-- Right section: color picker -->
    <div class="picker-section">
      <label for="colorInput">Choisir une couleur :</label><br>
      <input type="color" id="colorInput" value="#000000" />
      <button type="button" id="add-color-button">Ajouter la couleur</button>
    </div>
  </div>
</div>

    <div class="add-card">
        <h1 class="card-title">Ajouter une Couleur</h1>

  <div class="add-content">
    <form method="POST" action="/backend/save-association.php">
<p class="matiere-label">Sélectionner une ou plusieurs matières :</p>
<div id="matiere-checkboxes" class="matiere-options">
  <?php foreach ($models as $index => $matiere): ?>
    <?php 
      $inputId = 'matiere_' . $index;
      $idmatiere = htmlspecialchars($matiere['idmatiere']);
      $nommatiere = htmlspecialchars($matiere['nommatiere']);
    ?>
    <div class="checkbox-item">
      <input 
        type="checkbox" 
        id="<?= $inputId ?>" 
        name="matieres[]" 
        value="<?= $idmatiere ?>"
      >
      <label for="<?= $inputId ?>"><?= $nommatiere ?></label>
    </div>
  <?php endforeach; ?>

  <div id="colorList" class="palette"></div>

      <button type="submit">Ajouter la couleur</button>
</div>


    
<input type="hidden" id="colors-input" name="colors" value="[]">


  </form>
      <div class="picker-section">
      <label for="colorInputAssociation">Choisir une couleur :</label><br>
      <input type="color" id="colorInputAssociation" value="#000000" />
      <button type="button" id="add-association-color-button">Ajouter la couleur</button>
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
document.getElementById('add-product-form').addEventListener('submit', function (e) {
  e.preventDefault(); // empêche la soumission classique

  const formData = new FormData();

  const name = document.getElementById('product-name').value;
  const desc = document.getElementById('product-description').value;
  const photo = document.getElementById('photo-input').files[0];

  formData.append('product_name', name);
  formData.append('product_description', desc);
  if (photo) {
    formData.append('photo', photo);
  }

  fetch('/backend/save-product.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.text())
  .then(data => {
    console.log("✅ Réponse :", data);
    alert("✅ Modèle ajouté avec succès !");
  })
  .catch(err => {
    console.error("❌ Erreur :", err);
    alert("❌ Une erreur est survenue.");
  });
});
</script>

  <script>
  const dropArea = document.getElementById("photo-drop-area");
  const fileInput = document.getElementById("photo-input");

  // Ouvrir l'explorateur au clic
  dropArea.addEventListener("click", () => fileInput.click());

  // Quand un fichier est sélectionné
  fileInput.addEventListener("change", () => {
    const file = fileInput.files[0];
    if (file) previewImage(file);
  });

  // Gérer le drag & drop
  dropArea.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropArea.classList.add("dragover");
  });

  dropArea.addEventListener("dragleave", () => {
    dropArea.classList.remove("dragover");
  });

  dropArea.addEventListener("drop", (e) => {
    e.preventDefault();
    dropArea.classList.remove("dragover");
    const file = e.dataTransfer.files[0];
    if (file) {
      fileInput.files = e.dataTransfer.files; // mise à jour du champ input
      previewImage(file);
    }
  });

  // Afficher l'image dans le carré
  function previewImage(file) {
    const reader = new FileReader();
    reader.onload = function (e) {
      dropArea.innerHTML = `<img src="${e.target.result}" alt="Aperçu" />`;
    };
    reader.readAsDataURL(file);
  }
</script>

<script>
document.getElementById('add-color-button').addEventListener('click', function () {
  const color = document.getElementById('colorInput').value;
  const hiddenInput = document.getElementById('colors-input-matiere');

  // ✅ On récupère la valeur JSON actuelle
  let currentColors = [];
  try {
    currentColors = JSON.parse(hiddenInput.value || '[]');
  } catch (e) {
    console.error("Erreur de parsing JSON : ", e);
  }

  // ✅ On ajoute si elle n'existe pas
  if (!currentColors.includes(color)) {
    currentColors.push(color);
    hiddenInput.value = JSON.stringify(currentColors); // 🟢 mise à jour effective !
    console.log("✅ Nouvelle palette :", hiddenInput.value);
  }

  // 🔁 Génération du bloc visuel
  const box = document.createElement('div');
  box.className = 'color-box';

  const colorDiv = document.createElement('div');
  colorDiv.className = 'color';
  colorDiv.style.backgroundColor = color;

  const label = document.createElement('div');
  label.className = 'label';
  label.textContent = color;

  const closeBtn = document.createElement('button');
  closeBtn.className = 'close-btn';
  closeBtn.innerHTML = '×';
  closeBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    box.remove();

    // Mise à jour du tableau caché aussi !
    const newColors = currentColors.filter(c => c !== color);
    hiddenInput.value = JSON.stringify(newColors);
  });

  box.appendChild(closeBtn);
  box.appendChild(colorDiv);
  box.appendChild(label);
  document.getElementById('materialList').appendChild(box);
});
</script>



<script>
  // Supprimer une color-box au clic droit
  document.getElementById('materialList').addEventListener('contextmenu', function (e) {
    if (e.target.closest('.color-box')) {
      e.preventDefault(); // Empêche le menu clic droit par défaut
      const box = e.target.closest('.color-box');
      box.remove(); // Supprime le bloc
    }
  });
</script>
<script>
document.getElementById('add-association-color-button').addEventListener('click', function () {
  const color = document.getElementById('colorInputAssociation').value;
  const hiddenInput = document.getElementById('colors-input');

  let currentColors = JSON.parse(hiddenInput.value || '[]');
  if (!currentColors.includes(color)) {
    currentColors.push(color);
    hiddenInput.value = JSON.stringify(currentColors);
  }

  const box = document.createElement('div');
  box.className = 'color-box';

  const colorDiv = document.createElement('div');
  colorDiv.className = 'color';
  colorDiv.style.backgroundColor = color;

  const label = document.createElement('div');
  label.className = 'label';
  label.textContent = color;

  const closeBtn = document.createElement('button');
  closeBtn.className = 'close-btn';
  closeBtn.innerHTML = '×';
  closeBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    box.remove();
  });

  box.appendChild(closeBtn);
  box.appendChild(colorDiv);
  box.appendChild(label);

  document.getElementById('colorList').appendChild(box);
});
</script>
<script>
document.getElementById('add-material-form').addEventListener('submit', function (e) {
  e.preventDefault(); // empêche l'envoi classique du formulaire

  const materialName = document.getElementById('material-name').value;
  const colorsJson = document.getElementById('colors-input-matiere').value;

  // Envoi via fetch
  fetch('/backend/save-material.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded'
    },
    body: new URLSearchParams({
      material_name: materialName,
      colors: colorsJson
    })
  })
  .then(response => response.text())
  .then(data => {
    console.log("✅ Réponse serveur :", data);
    alert("✅ Matière et couleurs enregistrées !");
  })
  .catch(error => {
    console.error("❌ Erreur lors de l'enregistrement :", error);
    alert("❌ Une erreur est survenue.");
  });
});
</script>


</body>
</html>