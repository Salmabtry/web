<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarByte - Programmez votre prochain trajet</title>
    <!--lien vers le bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--lien vers notre fichier CSS-->
    <link rel="stylesheet" href="/CarByte/css/style.css">
</head>
<body>

    <header><a href="/CarByte/index.php">
        <img src="/CarByte/images/logo.png" alt="Logo CarByte"><!--logo du site-->
        <nav>
            <a href="/CarByte/index.php">Accueil</a>
            <a href="/CarByte/core/cars.php">Nos voitures</a>
            <a href="/CarByte/core/terms.php">Conditions</a>
            <a href="/CarByte/core/contact.php">Contact</a>
            
            <?php
            if (isset($_SESSION['user_id'])) { 
                // Si l'utilisateur est connecté, on affiche ces liens :
                echo "<a href='/CarByte/user/logout.php'>Se déconnecter</a>";
                
                if ($_SESSION['user_role'] == 'admin') {
                    echo "<a href='/CarByte/admin/admin.php'>Administration</a>";
                }
                if ($_SESSION['user_role'] == 'user') {
                    echo "<a href='/CarByte/core/reservations.php'>Réserver un véhicule</a>";
                }
                echo "<span>Bonjour " . $_SESSION['user_prenom'] . " !</span>";
                
            } else {
                // Si l'utilisateur N'EST PAS connecté, on affiche ces liens :
                echo "<a href='/CarByte/user/login.php'>Se connecter</a>"; // On le remet ICI
                echo "<a href='/CarByte/user/register.php'>S'inscrire</a>";
            }
            ?>
        </nav>
    </header>