<?php

session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conditions - CarByte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

            <?php if (isset($_SESSION['user_id'])) { ?> <!--verification si qlqn est connecté-->
                <a href="reservations.php">Réserver un véhicule</a>
                <a href="logout.php">Se déconnecter</a>
                <?php if ($_SESSION['user_role'] == 'admin') { ?> <!--si l'user est l'admin-->
                    <a href="admin.php">Administration</a>
                <?php } ?>
                <span>Bonjour <?php echo $_SESSION['user_prenom']; ?> !</span> <!--affichage du nom de l'user connecté-->
            <?php } else { ?> <!--si personne n'est connecté-->
                <a href="login.php">Se connecter</a>
                <a href="register.php">S'inscrire</a>
            <?php } ?>
        </nav>
    </header>

    <main>
        <h1>Conditions générales de location - CarByte</h1>
        <p>L'ensemble des conditions généérales présentes sur cette pages ont étés soigneusement réfléchies et rédigées par l'équipe CarByte afin que notre structure soit la plus sécuritaire possible, aussi bien pour nos utilisateurs que pour notre équipe. Nous vous prions et vous remercions  de les lire attentivement. Dès lors qu'une réservation est confirmée, vous êtes considéré comme ayant pris connaisance et accépté l'integrité de ces conditions. En cas d'incident, de dommages ou de litiges survenu pendant la période de location, la responsabilité reviendra entièrement à la personne ayant effectué la réservation. CarByte ne pourra pas être tenu responsable des conséquences liées à une mauvaise utilisation du véhicule.</p>

        <h2>Nos conditions.</h2>
        <ul>
            <li>Le loueur du véhicule doit être agé de minimum 21 ans et posséder un permis de conduire valide d'au moins 2 ans.</li>
            <li>Les documents suivants sont requis pour toute réservation : permis de conduire valide, carte d'identité ou passeport (pour les clients internationnaux) et une carte de crédit au nom du conducteur.</li>
            <li>une caution sera prélevée sur la carte de crédit du locataire lors de la prise en charge du véhicule. Son montant varie selon le véhicule loué et sera rendu dans un délai de 7 jours après le retour du véhicule, s'il y a absence de dommages.</li>
            <li>Le véhicule doit être retourné avec le même niveau de carburant qu'au moment de la prise en charge, c'est à dire avec le plein.</li>
            <li>Toute annulation ou modification de réservation  doit être réalisée au moins 48 heures avant l'heure et la date de réservation.</li>
            <li>Le loueur est est responsable de tout dommage causé au véhicule pendant la période de location, ainsi il est l'unique personne en droit de rouler la voiture.</li>
            <li>Toute amende ou infraction commise est à la charge du client.</li>
            <li>Le véhicule loué ne peut en aucun cas quitter le territoire belge. Toute sortie du véhicule sans l'accord au préalable de l'équipe CarByte est formellement interdite.</li>
            <li>Il est strictement interdit de fumer à l'interieur de tous les véhicules de CarByte.</li>
            <li>Les animaux de compagnies ne sont pas autorisés.</li>
            <li>La sous-location du véhicule est strictement interdite.</li>
            <li>En cas de panne ou d'incident, le client doit immédiatement contacter le service client de CarByte. Toute tentative de réparation par le client lui même est interdite.</li>
            <li>Chaque location inclut un kilométrage journalier maximum de 400 km.</li>
            <li>Le véhicule doit être réstitué propre, sans dommages supplémentaires par rapport à son état initial et à l'heure.</li>
        </ul>
        <br>
        <h2>Nos sanctions en cas d'infraction au contrat</h2>
        <ul>
            <li>CarByte se réserve le droit d'appliquer des sanctions finacières en cas de non-respect des conditions de locations.</li>
            <li>Toute infraction ou dommage constatée sera traitée sérieusement et les frais se verront directement déduits de la caution selon les faits. Dans le cas ou la caution ne couvrirait pas l'entièreté des frais, le clients sera tenu de régler la différence dans les plus bref délais, évitant un possible dépôt de plaintes dans les cas les plus graves.</li>
            <li>En cas de non restitué du véhicule avec le niveau de carburant requis, une pénalité de 50 euros sera retenu.</li>
            <li>En cas de restitution du véhicule sale ou avec des odeurs de tabac, des frais de nettoyages et de désodorisation d'un montant minimum de 50 euros seront appliqué"></li>
            <li>Si toute sortie non autoriséedu véhicule du territoire belge est constatée, des frais administratifs de 300 euros minimum seront facturés, auxquels s'ajouterons les éventuels frais de rapatriement du véhicule ainsi que les frais de fourrière applicabls.</li>
            <li>Tout kilomètre supplémentaire sépassant ka limite journalière de 400 km sera facturé à 0.25 centimes par kilomètre.</li>
            <li>Toute amende ou infraction au code de la route engendré pendant la période de location sera transmise au locataire qui se verra dans l'obligation de régler les frais dans les plus bref délais. De plus, l'amende sera mise au nom du locataire.</li>
            <li>En cas de non respect grave et répété des conditions de location, l'équipe CarByte se réserve le droit de refuser toute future réservation de la part du client concerné.</li>
        </ul>
    </main>

    <footer>
        <p>© 2026 CarByte - Tous droits réservés</p>
    </footer>

</body>
</html>