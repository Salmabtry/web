<?php
//ouverture de la session
session_start();

include('../header.php');
?>

    <main>

        <h1>Retrouvez-nous:</h1>

        <section>
            <h2>Nos coordonées:</h2>
            <p>Adresse: Boulevard du Triomphe 1 accès 2, 1050 Bruxelles</p>
            <p>Téléphone: +32 4 12 34 56 78</p>
            <p>Email: carbyte.location@outlook.be</p>
            <p>Horaires: Du lundi au vendredi - 10h00 à 16h00, samedi - 10h00 à 18h00</p>
        </section>

        <section>
            <h2>Contactez-nous:</h2><!--pour nous contacter on a mis en place un formulaire-->

            <form method="POST" action="contact.php"><!-- collecte des données en POST-->

                <label>Nom:</label><br>
                <input type="text" name="nom" required><br><br><!--nom-->

                <label>Email:</label><br>
                <input type="email" name="email" required><br><br><!-- type email vérifie que c'est un email valide qui est entré-->

                <label>Sujet:</label><br>
                <input type="text" name="sujet" required><br><br><!--objet du message-->

                <label>Message:</label><br>
                <textarea name="message" rows="5" required></textarea><br><br>

                <button type="submit">Envoyer</button>

            </form>

            <?php //vérification si le formulaire a bien été envoyer, if oui on envoie un message de confiration-->
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                echo "<p>Votre message a bien été envoyé ! Nous vous répondrons dans les plus brefs délais.</p>";
            }
            ?>

        </section>

        <section>
            <h2>SAV</h2>
            <p>Chez CarByte, nous sommes avec vous tout au long de la réservation, et non pas seulement au moment de la remise et de la récuperation des clefs. Nous avons une équipe attitré pour répondre à vos questions, assurer le service après-vente et le service d'urgence.te. Votre sécurité est notre prioritée.</p>
        </section>

        <section>
            <h2>Cellule d'urgence</h2>
            <p>Notre service d'urgence est ouvert 24/24 et 7/7, pour être prêt à vous aider en toutes circonstances.</p>
            <p>Numéro d'urgence: <strong>+32 4 123 456</strong>(appel gratuit)</p>
            <p>Notre équipe est fromé pour vous accompagner dans toutes situations.</p>
        </section>

        <section>
            <h2>Procédure en cas d'accident ou de panne</h2>
            <p>En cas d'accident, de panne ou de crevaison, veuillez suivre les étapes ci-dssous:</p>
            <ul>
                <li>Contactez immédiatement le +32 400 123 456. Nous nous chargerons d'appeler la police, les pompiers ou autre service compétent si la situation le nécéssite.</li>
                <li>En cas d'accident léger, nous nous chargerons de vous expliquer les étapes à suivre pour remplir le constat, prendre des photos de l'accident et assurer la suite du dossier.</li>
                <li>En cas d'accident grave, nous nous chargerons de toutes les démarches à faire pendant votre hospitalisation.</li>
                <li>En cas de panne ou de crevaison, nous vous enverons une assistance dans l'heure.</li>
                <li>CarByte prend en charge les pannes mécaniques et crevaison.</li>
            </ul>
        </section>
    </main>
