<?php

session_start();

include('db.php');

//accès restraint pour les visiteurs, if vérificateur que l'user est bien connecté 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

//traitement du formulaire en POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //récupération des données depuis la db
    $id_car = $_POST['id_car'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $id_user = $_SESSION['user_id'];

    //upload de la carte d'identité que l'user doit upload sur le site 
    if ($_FILES['carte_identite']['name'] != '') {
        $carte = $_FILES['carte_identite']['name'];
        move_uploaded_file($_FILES['carte_identite']['tmp_name'], "images/" . $carte);
    } else {
        $carte = '';
    }

    //insertion de la reservation avec le statut en attente
    $requete = $pdo->prepare("INSERT INTO reservations (id_user, id_cars, date_debut, date_fin, carte_identite, statut) 
                              VALUES (?, ?, ?, ?, ?, ?)");
    $requete->execute([$id_user, $id_car, $date_debut, $date_fin, $carte, 'en attente']);

    // On met la voiture en non disponible automatiquement
    $requete = $pdo->prepare("UPDATE cars SET disponible = 'non' WHERE id = ?");
    $requete->execute([$id_car]);

    //confirmation de la demande de reservation et attente de confirmations
    $_SESSION['message_succes'] = "Réservation envoyée! En attente de confirmation.";

    //msj de la page en la redirigeant vers la page 
    header("Location: reservations.php");
    exit();
}

//récupération de toutes les voiture depuis la db
$requete = $pdo->prepare("SELECT * FROM cars");
$requete->execute();
$cars = $requete->fetchAll(PDO::FETCH_ASSOC);

//récupérations des reservations de l'user
$requete = $pdo->prepare("SELECT reservations.*, cars.marque, cars.modele 
                          FROM reservations 
                          JOIN cars ON reservations.id_cars = cars.id 
                          WHERE reservations.id_user = ?");
$requete->execute([$_SESSION['user_id']]);
$mes_reservations = $requete->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include('header.php'); ?>

    <main>

        <h1>Réservations</h1>

        <?php if (isset($_SESSION['message_succes'])) { ?>
            <p><?php echo $_SESSION['message_succes']; ?></p>
            <?php unset($_SESSION['message_succes']); ?>
        <?php } ?>

        <!--liste voiture-->
        <section>
            <h2>Nos voitures</h2>
            <div class="row">
                <?php foreach ($cars as $car) { ?>

                    <div class="col-md-4">
                        <div class="car-card">
                            <img src="images/<?php echo $car['image']; ?>"alt="<?php echo $car['marque'] . ' ' . $car['modele']; ?>">
                        
                        <h3><?php echo $car['marque'] . ' ' . $car['modele']; ?></h3>
                        <p>Catégorie : <?php echo $car['categorie']; ?></p>
                        <p>Carburant : <?php echo $car['carburant']; ?></p>
                        <p>Places : <?php echo $car['places']; ?></p>
                        <p>Année : <?php echo $car['annee']; ?></p>
                        <p>Couleur : <?php echo $car['couleur']; ?></p>
                        <p>Boite : <?php echo $car['boite']; ?></p>
                        <p>Caution : <?php echo $car['caution']; ?> €</p>
                        <p>Prix : <?php echo $car['prix']; ?> € / jour</p>

                        <?php if ($car['disponible'] == 'oui') { ?>
                            <p>Disponible</p>

                            <!--formulaire de reservations-->
                            <form method="POST" action="reservations.php" enctype="multipart/form-data">

                                <!--champ caché de l'id-->
                                <input type="hidden" name="id_car" value="<?php echo $car['id']; ?>">

                                <label>Date de début :</label><br>
                                <input type="date" name="date_debut" required><br><br>

                                <label>Date de fin :</label><br>
                                <input type="date" name="date_fin" required><br><br>

                                <label>Carte d'identité :</label><br>
                                <input type="file" name="carte_identite" required><br><br>

                                <button type="submit">Réserver</button>

                            </form>

                        <?php } else { ?>
                            <p>Non disponible</p>
                        <?php } ?>

                    </div>
                </div>
            <?php } ?>
        </div>
        </section>

        <!--partie mes reservations-->
        <section>
            <h2>Mes réservations</h2>

            <?php if (count($mes_reservations) > 0) { ?>

                <table>
                    <tr>
                        <th>Voiture</th>
                        <th>Date du début</th>
                        <th>Date de fin</th>
                        <th>Statut</th>
                    </tr>

                    <?php foreach ($mes_reservations as $reservation) { ?>
                    <tr>
                        <td><?php echo $reservation['marque'] . ' ' . $reservation['modele']; ?></td>
                        <td><?php echo $reservation['date_debut']; ?></td>
                        <td><?php echo $reservation['date_fin']; ?></td>
                        <td><?php echo $reservation['statut']; ?></td>
                    </tr>
                    <?php } ?>

                </table>

            <?php } else { ?>
                <p>Vous n'avez pas encore de réservation.</p>
            <?php } ?>

        </section>

    </main>

    <footer>
        <p>© 2026 CarByte - Tous droits réservés</p>
    </footer>

</body>
</html>