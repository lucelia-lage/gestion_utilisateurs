<?php
    try{
        $db = new PDO("mysql:host=127.0.0.1;dbname=yolo", "root", ""); // pour se connecter à la bd
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // en cas d'erreur, PDO lance une exception 
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // résultats de la rêque te seront faits comme tableaux associatifs
        // $db->query = $db->exec
        $users = $db->query("SELECT * FROM user")->fetchAll(); // convertir les résultats de la reqûete en un tableau 
    }
    catch(Exception $e){ // une exception de la classe Error est également lancée. 
        //On capture cette erreur dans le bloc catch juste en dessous et on affiche les informations relatives à 
        //l’erreur avec la méthode getMessage() de la classe Error. : https://www.pierre-giraud.com/php-mysql-apprendre-coder-cours/exception-try-throw-catch/
        echo $e->getMessage();
    }
?>