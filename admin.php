<?php

session_start();

include('db.php');

//vérification si admin est connecté 
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: login.php");//redirection vers login.php si pas connecter
    exit();
}

//code pour supprimer une voiture
//GET commande donnée par l'ia
if (isset($_GET['supprimer'])) {
    //récupération de la voiture à supprimer
    $id = $_GET['supprimer'];

    //suppression de la voiture dans la db
    $requete = $pdo->prepare("DELETE FROM cars WHERE id = ?");
    $requete->execute([$id]);

    //recharge de la page après la suppression 
    header("Location: admin.php");
    exit();
}

//accepter une reservation
if (isset($_GET['confirmer'])) {
    $id_reservation = $_GET['confirmer'];
    $requete = $pdo->prepare("UPDATE reservations SET statut = 'confirmée' WHERE id = ?");
    $requete->execute([$id_reservation]);
    header("Location: admin.php");
    exit();
}

//refuser une reservation
if (isset($_GET['refuser'])) {
    $id_reservation = $_GET['refuser'];

    //récupération de l'id de la voiture pour la remettre dispo
    $requete = $pdo->prepare("SELECT id_cars FROM reservations WHERE id = ?");
    $requete->execute([$id_reservation]);
    $res = $requete->fetch(PDO::FETCH_ASSOC);

    //remise en disponible
    $requete = $pdo->prepare("UPDATE cars SET disponible = 'oui' WHERE id = ?");
    $requete->execute([$res['id_cars']]);

    //statut refusée
    $requete = $pdo->prepare("UPDATE reservations SET statut = 'refusée' WHERE id = ?");
    $requete->execute([$id_reservation]);

    header("Location: admin.php");
    exit();
}

//modification de la voiture
if (isset($_GET['modifier'])) {
    //récupération de l'id de la voiture à modifier
    $id_modifier = $_GET['modifier'];

    //récupération des infos de la voiture à modifier
    $requete = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
    $requete->execute([$id_modifier]);
    $car_modifier = $requete->fetch(PDO::FETCH_ASSOC);
}

//on transmet et récupère chaque données en POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $categorie = $_POST['categorie'];
    $carburant = $_POST['carburant'];
    $places = $_POST['places'];
    $annee = $_POST['annee'];
    $couleur = $_POST['couleur'];
    $boite = $_POST['boite'];
    $caution = $_POST['caution'];
    $prix = $_POST['prix'];
    $disponible = $_POST['disponible'];

    //maj de la photo de la voiture
    //venant de l'ia 
    if ($_FILES['image']['name'] != '') {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "images/" . $image);
    } else {
        //si pas de nouvelle donnée on garde l'ancienne
        $image = $_POST['ancienne_image'];
    }

    //if on modifie une voiture -> message de confirmation
    if (isset($_POST['id_voiture']) && $_POST['id_voiture'] != '') {
        $requete = $pdo->prepare("UPDATE cars SET marque=?, modele=?, categorie=?, carburant=?, places=?, annee=?, couleur=?, boite=?, caution=?, prix=?, image=?, disponible=? WHERE id=?");
        $requete->execute([$marque, $modele, $categorie, $carburant, $places, $annee, $couleur, $boite, $caution, $prix, $image, $disponible, $_POST['id_voiture']]);
        $message_succes = "Voiture modifiée avec succès!";
    } else {
    //si on ajoute une voiture -> message de confirmation
        $requete = $pdo->prepare("INSERT INTO cars (marque, modele, categorie, carburant, places, annee, couleur, boite, caution, prix, image, disponible) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $requete->execute([$marque, $modele, $categorie, $carburant, $places, $annee, $couleur, $boite, $caution, $prix, $image, $disponible]);
        $message_succes = "Voiture ajoutée avec succès!";
    }

    //refresh de la page
    header("Location: admin.php");
    exit();
}

//récupération de toutes les voitures dans la db
$requete = $pdo->prepare("SELECT * FROM cars");
$requete->execute();
$cars = $requete->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion - CarByte</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header>
        <img src="images/logo.png" alt="Logo CarByte">
        <nav>
            <a href="index.php">Accueil</a>
            <a href="cars.php">Nos voitures</a>
            <a href="terms.php">Conditions</a>
            <a href="contact.php">Contact</a>
            <span>Bonjour <?php echo $_SESSION['user_nom']; ?> !</span>
            <a href="logout.php">Se déconnecter</a>
        </nav>
    </header>

    <main>

        <h1>Gestion des voitures</h1>

        <!--message succes-->
        <?php if (isset($message_succes)) { ?>
            <p><?php echo $message_succes; ?></p>
        <?php } ?>

        <section>
            <h2>
                <?php
                //change le nom du formulaire en fonction de si on modifie ou ajoute une voiture
                if (isset($car_modifier)) {
                    echo "Modifier la voiture";
                } else {
                    echo "Ajouter une voiture";
                }
                ?>
            </h2>

            <form method="POST" action="admin.php" enctype="multipart/form-data">

                <!--champ cache pour l'admin mais pas php -> ia-->
                <!--si c'est vide on ajoute, rempli on modifie-->
                <input type="hidden" name="id_voiture" 
                value="<?php if(isset($car_modifier)) echo $car_modifier['id']; ?>">

                <!--champ caché pour garder l'ancienne image-->
                <input type="hidden" name="ancienne_image" 
                value="<?php if(isset($car_modifier)) echo $car_modifier['image']; ?>">

                <label>Marque :</label><br>
                <input type="text" name="marque" 
                value="<?php if(isset($car_modifier)) echo $car_modifier['marque']; ?>" required><br><br>

                <label>Modèle :</label><br>
                <input type="text" name="modele" 
                value="<?php if(isset($car_modifier)) echo $car_modifier['modele']; ?>" required><br><br>

                <label>Catégorie :</label><br>
                <select name="categorie">
                    <option value="citadine" <?php if(isset($car_modifier) && $car_modifier['categorie'] == 'citadine') echo 'selected'; ?>>Citadine</option>
                    <option value="SUV" <?php if(isset($car_modifier) && $car_modifier['categorie'] == 'SUV') echo 'selected'; ?>>SUV</option>
                    <option value="sportive" <?php if(isset($car_modifier) && $car_modifier['categorie'] == 'sportive') echo 'selected'; ?>>Sportive</option>
                </select><br><br>

                <label>Carburant :</label><br>
                <select name="carburant">
                    <option value="essence" <?php if(isset($car_modifier) && $car_modifier['carburant'] == 'essence') echo 'selected'; ?>>Essence</option>
                    <option value="diesel" <?php if(isset($car_modifier) && $car_modifier['carburant'] == 'diesel') echo 'selected'; ?>>Diesel</option>
                    <option value="electrique" <?php if(isset($car_modifier) && $car_modifier['carburant'] == 'electrique') echo 'selected'; ?>>Électrique</option>
                </select><br><br>

                <label>Places :</label><br>
                <input type="number" name="places" 
                value="<?php if(isset($car_modifier)) echo $car_modifier['places']; ?>" required><br><br>

                <label>Année :</label><br>
                <input type="number" name="annee" 
                value="<?php if(isset($car_modifier)) echo $car_modifier['annee']; ?>" required><br><br>

                <label>Couleur :</label><br>
                <input type="text" name="couleur" 
                value="<?php if(isset($car_modifier)) echo $car_modifier['couleur']; ?>" required><br><br>

                <label>Boite :</label><br>
                <select name="boite">
                    <option value="manuelle" <?php if(isset($car_modifier) && $car_modifier['boite'] == 'manuelle') echo 'selected'; ?>>Manuelle</option>
                    <option value="automatique" <?php if(isset($car_modifier) && $car_modifier['boite'] == 'automatique') echo 'selected'; ?>>Automatique</option>
                </select><br><br>

                <label>Caution (€) :</label><br>
                <input type="number" name="caution" 
                value="<?php if(isset($car_modifier)) echo $car_modifier['caution']; ?>" required><br><br>

                <label>Prix par jour (€) :</label><br>
                <input type="number" name="prix" 
                value="<?php if(isset($car_modifier)) echo $car_modifier['prix']; ?>" required><br><br>

                <label>Image :</label><br>
                <input type="file" name="image"><br><br>

                <label>Disponible :</label><br>
                <select name="disponible">
                    <option value="oui" <?php if(isset($car_modifier) && $car_modifier['disponible'] == 'oui') echo 'selected'; ?>>Oui</option>
                    <option value="non" <?php if(isset($car_modifier) && $car_modifier['disponible'] == 'non') echo 'selected'; ?>>Non</option>
                </select><br><br>

                <!--bouton modifier ou envoyer par raport a l'action qu'on a sélectionner au debut-->
                <button type="submit">
                    <?php
                    if (isset($car_modifier)) {
                        echo "Modifier";
                    } else {
                        echo "Ajouter";
                    }
                    ?>
                </button>

            </form>
        </section>

        <section>
            <h2>Liste des voitures</h2>

            <?php if (count($cars) > 0) { ?>

                <table><!--tableau-->
                    <tr><!--une ligne du tableau-->
                        <th>Image</th><!--une cellule titre du tableau-->
                        <th>Marque</th>
                        <th>Modèle</th>
                        <th>Catégorie</th>
                        <th>Carburant</th>
                        <th>Places</th>
                        <th>Année</th>
                        <th>Couleur</th>
                        <th>Boite</th>
                        <th>Caution</th>
                        <th>Prix/jour</th>
                        <th>Disponible</th>
                        <th>Actions</th>
                    </tr>

                    <?php foreach ($cars as $car) { ?>
                    <tr>
                        <td><img src="images/<?php echo $car['image']; ?>" width="100"></td>
                        <td><?php echo $car['marque']; ?></td><!--une cellule normal du tableau-->
                        <td><?php echo $car['modele']; ?></td>
                        <td><?php echo $car['categorie']; ?></td>
                        <td><?php echo $car['carburant']; ?></td>
                        <td><?php echo $car['places']; ?></td>
                        <td><?php echo $car['annee']; ?></td>
                        <td><?php echo $car['couleur']; ?></td>
                        <td><?php echo $car['boite']; ?></td>
                        <td><?php echo $car['caution']; ?> €</td>
                        <td><?php echo $car['prix']; ?> €</td>
                        <td><?php echo $car['disponible']; ?></td>
                        <td>
                            <!--bouton modifier-->
                            <a href="admin.php?modifier=<?php echo $car['id']; ?>">Modifier</a>
                            <!--bouton supprimer-->
                            <a href="admin.php?supprimer=<?php echo $car['id']; ?>">Supprimer</a>
                        </td>
                    </tr>
                    <?php } ?>

                </table>

            <?php } else { ?>
                <p>Aucune voiture pour le moment.</p>
            <?php } ?>

        </section>

        <!--gestion des reservations-->
        <section>
            <h2>Réservations en attente</h2>

            <?php
            //on récupère les réservations et les infos user/voiture
            $requete = $pdo->prepare("SELECT reservations.*, 
                                    cars.marque, cars.modele,
                                    users.nom, users.prenom, users.email
                                    FROM reservations
                                    JOIN cars ON reservations.id_cars = cars.id
                                    JOIN users ON reservations.id_user = users.id
                                    ORDER BY reservations.id DESC");
            $requete->execute();
            $reservations = $requete->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <?php if (count($reservations) > 0) { ?>

                <table>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Voiture</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Carte d'identité</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>

                    <?php foreach ($reservations as $reservation) { ?>
                    <tr>
                        <td><?php echo $reservation['nom'] . ' ' . $reservation['prenom']; ?></td>
                        <td><?php echo $reservation['email']; ?></td>
                        <td><?php echo $reservation['marque'] . ' ' . $reservation['modele']; ?></td>
                        <td><?php echo $reservation['date_debut']; ?></td>
                        <td><?php echo $reservation['date_fin']; ?></td>
                        <td>
                            <!--lien pour voir la carte d'identité-->
                            <a href="images/<?php echo $reservation['carte_identite']; ?>" target="_blank">
                                Voir la carte
                            </a>
                        </td>
                        <td><?php echo $reservation['statut']; ?></td>
                        <td>
                            <!--bouton confirmer-->
                            <a href="admin.php?confirmer=<?php echo $reservation['id']; ?>">Confirmer</a>
                            <!--bouton refuser-->
                            <a href="admin.php?refuser=<?php echo $reservation['id']; ?>">Refuser</a>
                        </td>
                    </tr>
                    <?php } ?>

                </table>

            <?php } else { ?>
                <p>Aucune réservation pour le moment.</p>
            <?php } ?>

        </section>
    </main>

    <footer>
        <p>© 2026 CarByte - Tous droits réservés</p>
    </footer>

</body>
</html>