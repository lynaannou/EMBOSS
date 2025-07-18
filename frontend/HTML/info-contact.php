<?php
$pdo = require_once '../../backend/db.php';

// Récupère les contacts qui ont accepté d'être contactés
$sql = "SELECT nom, prenom, email FROM contact WHERE contactable = true";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Contacts - EMBOSS</title>
  <link rel="stylesheet" href="../Styles/info-contact.css" />
</head>
<body>
    
    <header>
        <div class ="logo-title" >
            <img src ="logo-EMBOSS-nouveau.png" alt = "Logo"/>
            
        </div>
        <nav>
      <a href="home.html">HOME</a>
      <a href="product.php">PRODUCT</a>
      <a href="catalogue.php">CATALOGUE</a>
      <a href="#">PROJECTS</a>
      <a href="contact.html">CONTACT</a>
    </nav>
    </header>
 <h1>Centre de contact</h1>

  <div class="container">
    <!-- Partie gauche : Liste des contacts -->
    <div class="contacts-table">
      <h2>Utilisateurs contactables</h2>
      <table>
        <thead>
          <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($contacts as $c): ?>
            <tr>
              <td><?= htmlspecialchars($c['nom']) ?></td>
              <td><?= htmlspecialchars($c['prenom']) ?></td>
              <td><?= htmlspecialchars($c['email']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Partie droite : Formulaire message -->
    <div class="message-form">
      <h2>Envoyer un message groupé</h2>
      <form method="POST" action="../../backend/send-email-mass.php">
        <label for="subject">Sujet :</label>
        <input type="text" name="subject" id="subject" required>

        <label for="message">Message :</label>
        <textarea name="message" id="message" rows="10" required></textarea>

        <button type="submit">Envoyer à tous</button>
      </form>
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
</body>
</html>
