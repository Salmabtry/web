<?php
//on démarre la session
session_start();

//appel du fichier comprenant la connexion à la db
include('db.php');

//récupération du contenu de la page acceuil depuis la table "content" dans la db
$requete = $pdo->prepare("SELECT * FROM content WHERE id = 1");
$requete->execute();
$content = $requete->fetch(PDO::FETCH_ASSOC);//mise en forme de tableau associatif
?>

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

    <header><!--en-tête du site-->
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

    <main>

        <section><!--partie concernant le slogan-->
            <?php
            //if vérificateur si les données existent dans la db
            if ($content) {
                echo "<h1>" . $content['titre'] . "</h1>";
                echo "<p>" . $content['slogan'] . "</p>";
                echo "<div>" . $content['texte'] . "</div>";
            } else {
                
                echo "<h1>Bienvenue sur CarByte</h1>";
                echo "<p>CarByte - Programmez votre prochain trajet.</p>";
            }
            ?>
        </section>

        <?php
        //si l'user qui s'est connecté est l'admin on affiche le formulaire CKEditor pour modifier le contenu
        if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'admin') {
        ?>
            <section>
                <h2>Modifier le contenu de la page d'acceuil</h2>

                <form method="POST" action="index.php"><!--données envoyer en POST quand on clique sur sauvegarder -- le formulaire s'envoie à lui même-->

                    <label>Titre :</label><br>
                    <input type="text" name="titre" 
                    value="<?php if($content) echo $content['titre']; ?>" required><br><br><!--si le titre existe dans la db on l'affiche, sinon on laisse vide-->

                    <label>Slogan :</label><br>
                    <input type="text" name="slogan" 
                    value="<?php if($content) echo $content['slogan']; ?>" required><br><br><!--pareil pour le titre-->

                    <label>Texte principal :</label><br>
                    <!--CKEditor s'appliquera ici-->
                    <textarea name="texte" id="texte"><!--textarea pour un grand bloc de texte-->
                        <?php if($content) echo $content['texte']; ?><!--on affiche le texte catuel de la db dans le textearea-->
                    </textarea><br><br>

                    <button type="submit" name="sauvegarder">Sauvegarder</button><!--envoi du formulaire en POST-->

                </form>

                <!--on charge le CKEditor depuis internet - aide de l'ia-->
                <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
                <script>
                    //on applique le CKEditor sur le textearea ayant l'id texte
                    CKEDITOR.replace('texte');
                </script>

            </section>

        <?php
        }

        //on vérifie que le formulaire a été soumsi en POST et que c'est bien le bouton "sauvegarder" qui a été cliqué
        //&& = les deux doivent être vraies
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sauvegarder'])) {

            //on récupère nos 3 valeurs entrées par l'admin 
            $titre = $_POST['titre'];
            $slogan = $_POST['slogan'];
            $texte = $_POST['texte'];

            //if un contenu existe deja en db
            if ($content) {
                //on met à jour les colonnes titre,slogan et texte de la ligne 1 de la table 
                $requete = $pdo->prepare("UPDATE content SET titre = ?, slogan = ?, texte = ? WHERE id = 1");
                $requete->execute([$titre, $slogan, $texte]);
            } else { //else pas de contenu dans la db
                //on crée une nouvelle ligne 
                $requete = $pdo->prepare("INSERT INTO content (titre, slogan, texte) VALUES (?, ?, ?)");
                $requete->execute([$titre, $slogan, $texte]);
            }

            //on recharge la page
            header("Location: index.php");
            exit();
        }
        ?>

    </main>

    <!--pied de page du site-->
    <footer>
        <?php
        //si le contenu existe on l'affiche
        if ($content) {
            echo "<p>" . $content['footer'] . "</p>";
        } else {
            echo "<p>© 2026 CarByte - Tous droits réservés</p>";//sinon on prevoit un texte par defaut
        }
        ?>
    </footer>

</body>
</html>