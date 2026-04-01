<?php

session_start();

include('db.php');

//récupération de toutes les voiture de la table cars dans la db
$requete = $pdo->prepare("SELECT * FROM cars");
//on envoi la requête à la db
$requete->execute();
//on récupère les résultats d'un coup 
//fetchALL() commande venant de l'ia
$cars = $requete->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><!--ligne de code pour adapter la page aux mobiles-->
    <title>Nos voitures - CarByte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header>
        <img src="images/logo.png" alt="Logo CarByte">
        <nav>
            <!--lien de navigation fixes-->
            <a href="index.php">Accueil</a>
            <a href="cars.php">Nos voitures</a>
            <a href="terms.php">Conditions</a>
            <a href="contact.php">Contact</a>

            <?php
            //vérification si qlqn est connecté
            if (isset($_SESSION['user_id'])) {
                //si oui message "Bonjour user" + page déconnexion
                echo "<a href='logout.php'>Se déconnecter</a>";
                //si admin alors +page admin
                if ($_SESSION['user_role'] == 'admin') {
                    echo "<a href='admin.php'>Administration</a>";
                }
                //si user alors +page voiture disponibles
                if ($_SESSION['user_role'] == 'user') {
                    echo "<a href='reservations.php'>Réserver un véhicule</a>";
                }
                echo "<span>Bonjour " . $_SESSION['user_prenom'] . " !</span>";
            } else {
                //sinon page connexion ou inscription
                echo "<a href='login.php'>Se connecter</a>";
                echo "<a href='register.php'>S'inscrire</a>";
            }
        ?>
        </nav>
    </header>

    <main>

        <h1>Nos voitures</h1>

        <!--vérification s'il y a des voitures dans la db - "count = compte le nombre-->
        <?php if (count($cars) > 0) { ?> <!--s'il y en a au moins une-->

        <div class="row">
            <!--boucle sur chaque voiture une à une-->
            <?php foreach ($cars as $car) { ?>

                <div class="col-md-4">
                    <div class="car-card">
                        <!--photo de la voiture-->
                        <img src="images/<?php echo $car['image']; ?>" alt="<?php echo $car['marque'] . ' ' . $car['modele']; ?>">
                        
                        <!--infos de la voiture-->
                        <h2><?php echo $car['marque'] . ' ' . $car['modele']; ?></h2>
                        <p>Catégorie: <?php echo $car['categorie']; ?></p>
                        <p>Carburant: <?php echo $car['carburant']; ?></p>
                        <p>Places: <?php echo $car['places']; ?></p>
                        <p>Année: <?php echo $car['annee']; ?></p>
                        <p>Couleur: <?php echo $car['couleur']; ?></p>
                        <p>Boite: <?php echo $car['boite']; ?></p>
                        <p>Caution: <?php echo $car['caution']; ?> €</p>
                        <p>Prix: <?php echo $car['prix']; ?> €/jour</p>
                        
                        <!--disponibilité de la voiture-->
                        <?php if ($car['disponible'] == 'oui') { ?>
                            <p>Disponible</p>
                        <?php } else { ?>
                            <p>Non disponible</p>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>

        <?php } else { ?>
            <!--message si aucune voiture n'a été trouvé dans la db-->
            <p>Aucune voiture disponible pour le moment.</p>
        <?php } ?>

    </main>

    <footer>
        <p>© 2026 CarByte - Tous droits réservés</p>
    </footer>

</body>
</html>