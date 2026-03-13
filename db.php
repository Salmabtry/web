<?php 
//try et catch = gerer les erreurs de connexion
try {
    $pdo = new PDO("mysql:host=localhost;dbname=carbyte", "root", ""); //connexion à la db
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//affichage des erreurs 
} catch (Exception $e) { //si la connexion à échoue on arrive dans cet partie du code
    die("Erreur de connexion : " . $e->getMessage());
}
?>