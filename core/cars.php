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

<?php include('header.php'); ?>

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