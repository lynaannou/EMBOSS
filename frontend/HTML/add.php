<?php
$pdo = require_once '../../backend/db.php';

$sql = "SELECT idmatiere, nommatiere FROM matiere ORDER BY nommatiere ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$matieres = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <h1>Ajouter un Model</h1>
    <div class="add-card">

      <form id="add-product-form" method="POST" action="/backend/save-product.php">
        <label for="product-name">Nom du model</label>
        <input type="text" id="product-name" name="product_name" required />

        <label for="product-description">Description</label>
        <textarea id="product-description" name="product_description" rows="5" required></textarea>

        <button type="submit">Ajouter</button>
      </form>

        <div class="add-photo" id="photo-drop-area">
          <span class="plus-icon">+</span>
          <input type="file" id="photo-input" name="photo" accept="image/*" hidden />
        </div>

    </div>


    <h1>Ajouter une Matière</h1>
    <div class="add-card">
    <div class="left-section">
      <form id="add-material-form" class="material-form" method="POST" action="/backend/save-material.php">
        <label for="material-name">Nom de la nouvelle matière</label>
        <input type="text" id="material-name" name="material_name" />
        <div id="materialList" class="palette"></div>
        <button type="submit">Ajouter la matière</button>
      </form>

      
    </div>

    <div class="right-section">
    
        <label for="colorInput">Choisir une couleur :</label>
        <input type="color" id="colorInput" value="#000000" />
        <textarea id="hexInput" rows="1" placeholder="#996FD1" style="width: 160px; resize: none;"></textarea>
        <button type="button" id="add-color-button">Ajouter la couleur</button>
      
    </div>
    
  </div>


    <h1>Ajouter une couleur</h1>
<div class="add-card">
     <div class="left-section">
      <form id="add-material-form" class="material-form" method="POST" action="/backend/save-material.php">
        <label for="material-name">Choisir une matière</label>
         <select id="select-matiere" name="idmatiere" required>
      <?php foreach ($matieres as $matiere): ?>
        <option value="<?= htmlspecialchars($matiere['idmatiere']) ?>">
          <?= htmlspecialchars($matiere['nommatiere']) ?>
        </option>
      <?php endforeach; ?>
    </select>
        <div id="materialListExisting" class="palette"></div>
        <button type="submit">Ajouter la matière</button>
      </form>

      
    </div>

    <div class="right-section">
    
        <label for="colorInput">Choisir une couleur :</label>
        <input type="color" id="colorInput" value="#000000" />
        <textarea id="hexInput" rows="1" placeholder="#996FD1" style="width: 160px; resize: none;"></textarea>
        <button type="button" id="add-color-button-existing">Ajouter la couleur</button>

      
    </div> 
</div>

    
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
    const name = document.getElementById('material-name').value.trim();
    
    let colorInputValue = document.getElementById('colorInput').value;

    const hexInput = document.getElementById('hexInput');
    let hexValue = document.getElementById('hexInput').value.trim();

    // Si hexValue est rempli et valide, on l’utilise à la place du color picker
    let color = /^#([0-9A-F]{3}){1,2}$/i.test(hexValue) ? hexValue : colorInputValue;
    
    if (hexValue && !/^#([0-9A-F]{3}){1,2}$/i.test(hexValue)) {
    alert("Le code hexadécimal est invalide. Exemple valide : #996FD1");
    hexInput.value = '';
    return;
  }


    // Crée le conteneur principal
    const box = document.createElement('div');
    box.className = 'color-box';

    // Crée la div de couleur
    const colorDiv = document.createElement('div');
    colorDiv.className = 'color';
    colorDiv.style.backgroundColor = color;

    // Crée le label (nom/couleur)
    const label = document.createElement('div');
    label.className = 'label';
    label.textContent = name ? `${name} (${color})` : `${color}`;

    // Crée le bouton de suppression
    const closeBtn = document.createElement('button');
    closeBtn.className = 'close-btn';
    closeBtn.innerHTML = '×';
    closeBtn.addEventListener('click', (e) => {
      e.stopPropagation(); // Évite les bugs de propagation
      box.remove();
    });

    // Assemble les éléments
    box.appendChild(closeBtn);  // IMPORTANT : ajouter le bouton EN PREMIER
    box.appendChild(colorDiv);
    box.appendChild(label);

    // Ajoute à la liste
    document.getElementById('materialList').appendChild(box);

    document.querySelector('.right-section').style.height = '450px';

    document.getElementById('colorInput').style.width = '220px';
    document.getElementById('colorInput').style.height = '220px';
    
    hexInput.value = '';
  });
</script>


<script>
  // Supprimer une color-box au clic droit
  document.getElementById('materialList').addEventListener('contextmenu', function (e) {
    if (e.target.closest('.color-box')) {
      e.preventDefault(); // Empêche le menu clic droit par défaut
      const box = e.target.closest('.color-box');
      box.remove(); // Supprime le bloc

      if (document.querySelectorAll('.color-box').length === 0) {
    document.querySelector('.right-section').style.height = '';
    document.getElementById('colorInput').style.width = '160px';
    document.getElementById('colorInput').style.height = '160px';
      }
    }
  });
</script>

<script>
  document.getElementById('add-color-button-existing').addEventListener('click', function () {
    const select = document.getElementById('select-matiere');
    const selectedText = select.options[select.selectedIndex].text;

    let colorInputValue = document.getElementById('colorInput').value;
    const hexInput = document.getElementById('hexInput');
    let hexValue = hexInput.value.trim();

    let color = /^#([0-9A-F]{3}){1,2}$/i.test(hexValue) ? hexValue : colorInputValue;

    if (hexValue && !/^#([0-9A-F]{3}){1,2}$/i.test(hexValue)) {
      alert("Le code hexadécimal est invalide. Exemple valide : #996FD1");
      hexInput.value = '';
      return;
    }

    const box = document.createElement('div');
    box.className = 'color-box';

    const colorDiv = document.createElement('div');
    colorDiv.className = 'color';
    colorDiv.style.backgroundColor = color;

    const label = document.createElement('div');
    label.className = 'label';
    label.textContent = selectedText ? `${selectedText} (${color})` : `${color}`;

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

    document.getElementById('materialListExisting').appendChild(box);

    hexInput.value = '';
  });
</script>


</body>
</html>