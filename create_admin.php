<?php
include('db.php');

//vérification si un admin existe deja ou non
//sinon le code crée un admin à chaque fois qu'on lance le fichier
$requete = $pdo->prepare("SELECT * FROM users WHERE role = 'admin'");
$requete->execute();
$admin = $requete->fetch(PDO::FETCH_ASSOC);

//if un admin existe deja on arrete le code
if ($admin) {
    die("Un compte admin existe déjà !");
}

//sinon ce code se lance
$password_crypte = password_hash("Helb2026", PASSWORD_DEFAULT);
$requete = $pdo->prepare("INSERT INTO users (nom, prenom, email, password, role) VALUES (?, ?, ?, ?, ?)");
$requete->execute(["CarByte", "Admin", "carbyte.location@outlook.be", $password_crypte, "admin"]);

echo "Compte admin créé avec succès !";
?>
