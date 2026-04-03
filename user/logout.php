<?php
//start de la session (car toujours demarrer la session pour pouvoir la manipuler)
session_start();

// On vide toutes les variables de la session (nom, prenom,...)
session_unset();

//destruction de la session
session_destroy();

//redirection vers la page d'acceuil générale
header("Location: login.php");
exit();//arrêt du script
?>