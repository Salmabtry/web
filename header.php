<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarByte - Programmez votre prochain trajet</title>
    <!--lien vers le bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--lien vers notre fichier CSS-->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header><a href="index.php">
        <img src="images/logo.png" alt="Logo CarByte"><!--logo du site-->

        <nav>
            <a href="index.php">Accueil</a>
            <a href="cars.php">Nos voitures</a>
            <a href="terms.php">Conditions</a>
            <a href="contact.php">Contact</a>

            <?php
            if (isset($_SESSION['user_id'])) { //if user connecter lien vers la page deconnexion 
                echo "<a href='logout.php'>Se déconnecter</a>";
                if ($_SESSION['user_role'] == 'admin') { //si admin alors lien vers la page admin
                    echo "<a href='admin.php'>Administration</a>";
                }
                if ($_SESSION['user_role'] == 'user') { //si user alors lien vers la page voiture disponible
                    echo "<a href='reservations.php'>Réserver un véhicule</a>";
                }
                echo "<span>Bonjour " . $_SESSION['user_prenom'] . " !</span>"; //message de bienvenue avec le prénom de l'user
            } else {
                echo "<a href='login.php'>Se connecter</a>";
                echo "<a href='register.php'>S'inscrire</a>";
            }
            ?>
</nav>
    </header>