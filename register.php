<?php

session_start();//on demarre la session

include('db.php');//on apelle le fichier de connexion à la db pour eviter de devoir mettre le code de connexion à chaque fichier

//vérification si le sdonnées ont bien étés envoyées
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //récupération des données entré par l'user  
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    //un seul rôle possible pour l'user (pas la possibilité de créer un compte admin)
    $role = "user";

    //cryptage du mdp avant qu'il soit sauvegardé sinon il sera visible dans la db
    //code de cryptage généré avec l'IA 
    $password_crypte = password_hash($password, PASSWORD_DEFAULT);

    //requête pour ajouter une nouvelle ligne dans la table user 
    //$requete = prépare la requête, prepapre = prépare la requete, ? = emplacement vide
    $requete = $pdo->prepare("INSERT INTO users (nom, prenom, email, password, role) 
                              VALUES (?, ?, ?, ?, ?)");

    //exécution de la requête en changeant les ?
    //renvoie "true" si la requête a réussi et l'user ajouté dans la db
    //renvoie "flase" si la requête a échoué 
    $resultat = $requete->execute([$nom, $prenom, $email, $password_crypte, $role]);

    //if = si la requête a réussi
    //else = si la requête a échoué
    if ($resultat) {
        $message_succes = "Compte créé avec succès!";
    } else {
        $message_erreur = "Erreur lors de la création du compte.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <!--definition de l'encodage universelle-->
    <meta charset="UTF-8">
    <!--ligne de code pour rendre un site compatible sur modbile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - CarByte</title>
</head>
<body>

    <h1>Création du compte</h1>

    <?php
    //affichage du message de création de compte
    if (isset($message_succes)) {
        echo "<p>" . $message_succes . "</p>";
    }
    //affichage du message d'erreur de création de compte
    if (isset($message_erreur)) {
        echo "<p>" . $message_erreur . "</p>";
    }
    ?>

   <!--formulaire HTML que l'user voit sur la page--> 
    <form method="POST" action="register.php"> <!-- envoie les données en POST-->

        <label>Nom :</label><br><!--étiquette Nom-->
        <input type="text" name="nom" required><br><br>

        <label>Prénom :</label><br><!--étiquette Prénom-->
        <input type="text" name="prenom" required><br><br>

        <label>Email :</label><br><!--étiquette Email-->
        <input type="email" name="email" required><br><br><!--le navigateur vérifie que c'est une forme d'email valide grâce au type="mail"-->

        <label>Mot de passe :</label><br><!--étiquette Mot de passe-->
        <input type="password" name="password" required><br><br><!--le type="password" va caché le mdp avec des points-->

        <button type="submit">S'inscrire</button><!--submit = envoi le formulaire en POST-->

    </form>

</body>
</html>