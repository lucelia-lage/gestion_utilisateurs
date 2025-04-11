<?php
require "db.php";

if (!isset($_GET['id'])) {
    echo "L'ID n'a pas été renseigné.";
}

$id = intval($_GET['id']);

try {
    $selectUser = $db->prepare("SELECT * FROM user WHERE id = :id"); // on prépare notre requête

    $selectUser->bindParam(':id', $id); // La méthode bindParam() va lier un paramètre à un nom de variable 
    // spécifique et la variable va être liée en tant que référence et ne 
    // sera évaluée qu’au moment de l’appel à la méthode execute().: https://www.pierre-giraud.com/php-mysql-apprendre-coder-cours/requete-preparee/ 

    $selectUser->execute(); // on exécute la requête

    $user = $selectUser->fetch(); // résultats de la rêque te seront faits comme tableaux associatifs

    if (!$user) {
        echo "Cet utilisateur n'existe pas.";
    }
} catch (Exception $e) { // une exception de la classe Error est également lancée. 
    echo $e->getMessage(); // pour afficher le message d'erreur
}

if (isset($_POST['updateUser'])) {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $mail = $_POST['mail'];
    $zipCode = $_POST['zipCode'];

    try {
        $db->beginTransaction(); // on active la vérification
        $updateUser = $db->prepare("UPDATE user SET firstName = :firstName, lastName = :lastName, mail = :mail, zipCode = :zipCode WHERE id = :id "); // on prépare notre requête
        
        $updateUser->bindParam(":firstName", $firstName);
        $updateUser->bindParam(":lastName", $lastName);
        $updateUser->bindParam(":mail", $mail);
        $updateUser->bindParam(":zipCode", $zipCode);
        $updateUser->bindParam(":id", $id);

        $updateUser->execute(); // on exécute la requête

        $db->commit(); // tout s'est bien passé ? alors on valide la transaction
        header("Location: index.php");
    } catch (Exception $e) { // message d’erreur à afficher si une exception a été soulevée
        echo "<pre>",var_dump($e),"</pre>";
        $db->rollBack(); // permet de restaurer la table à sa valeur initiale si une exception est soulevée
        echo $e->getMessage(); // pour afficher le message d'erreur
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier un utilisateur</title>
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <h1>Modifier un utilisateur</h1>

    <form action="modif.php?id=<?= $id ?>" method="POST">
        <input type="text" name="firstName" placeholder="Prénom" value="<?= $user['firstName'] ?>"><br>
        <input type="text" name="lastName" placeholder="Nom" value="<?= $user['lastName'] ?>"><br>
        <input type="email" name="mail" placeholder="Email" value="<?= $user['mail'] ?>"><br>
        <input type="text" name="zipCode" placeholder="Code postal" value="<?= $user['zipCode'] ?>"><br>
        <button type="submit" name="updateUser">Enregistrer</button>
    </form>

    <p><a href="index.php">Retourner à la liste</a></p>
</body>

</html>