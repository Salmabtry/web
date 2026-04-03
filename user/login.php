<?php

session_start();//on démarre la session

include('../db.php');//comme pour register.php on appelle le fichier de connexion à la base de donnée
include('../header.php');

//vérification si les données ont bien été envoyées
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    //récupération des données entré par l'user
    $email = $_POST['email'];
    $password = $_POST['password'];

    //on cherche dans toutes les colonnes (*) de la table users et dans la colonnes email (? emplacement pour la vrai valeur qui sera entré)
    $requete = $pdo->prepare("SELECT * FROM users WHERE email = ?");//préparation sécurisé de la requête
    $requete->execute([$email]);//envoi de la requête à la db - $email = ce que l'utilisateur aura entré

    //résultat sous forme de tableau pour une meilleur lisibilité grâce à la commande "fetch_assoc"
    $user = $requete->fetch(PDO::FETCH_ASSOC);

    //si user trouvé
    //$user contient le résultat de fetch 
    if ($user) {
        //alors on compare le mdp entré avec celui enregistré dans la db (qui est crypté) grâce au password_verify
        //commande "password_verify" faites avec l'ia
        if (password_verify($password, $user['password'])) { //$password = mdp entré par l'user -- $user['password'] = le mdp crypté dans la db

            //si le mdp correspond alors on fait ca 
            //on stocke les données de l'user dans la session           
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_prenom'] = $user['prenom'];
            $_SESSION['user_role'] = $user['role']; //grâce au role stocké dans la db on sait si c'est l'user ou l'admin

            //redirection vers la page d'accueil ->index.php
            //commande trouvé avec l'ia header
            header("Location: ../index.php");
            exit();//php ne lit plus rien apres le exit

        } else {
            //sinon si le mdp est inccorect 
            $message_erreur = "Mot de passe incorrect.";
        }

    } else {
        //sinon si aucun user a été trouvé avec l'email entré
        $message_erreur = "Aucun compte trouvé avec cet email.";
    }
}
?>

    <main>
        <div class="row justify-content-center">
            <div class="col-md-5">
                <h1>Connexion</h1>

                <?php
                //si un message d'erreur existe alors on l'affiche
                if (isset($message_erreur)) {
                    echo "<p class='alert alert-danger'>" . $message_erreur . "</p>";
                }
                ?>

                <!--formulaire de connexion -- données envoyés en POST-->
                <form method="POST" action="login.php">

                    <div class="mb-3">
                        <label>Email :</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label>Mot de passe :</label>
                        <input type="password" name="password" required>
                    </div>

                    <button type="submit" class="btn">Se connecter</button>

                </form>

                <!--option si on n'a pas encore de compte, redirection vers la page d'inscription-->
                <p class="mt-3">Pas encore de compte ? <a href="register.php">S'inscrire</a></p>
            </div>
        </div>
    </main>
</body>
</html>