<?php require "db.php"; ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <title>Projet Final SQL / PHP</title>
</head>

<body>
    <h1>Gestion d'utilisateurs</h1>
<div class= "formContainer">
    <h2>Nouvel utilisateur</h2>
    <form action="index.php" method="POST">
        <input type="text" name="firstName" placeholder="Prénom"><br>
        <input type="text" name="lastName" placeholder="Nom"><br>
        <input type="email" name="mail" placeholder="Adresse mail"><br>
        <input type="text" name="zipCode" placeholder="Code Postal"><br>
        <button type="submit" name="addUser">Ajouter</button>
    </form>
</div>
    <h2>Utilisateurs enregistrés</h2>
    <table>
        <tr>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Adresse-mail</th>
            <th>Code postal</th>
            <th></th>
        </tr>
        <?php
        foreach ($users as $entry) { // on parcout tous les résultats 
            echo "<tr>";
            echo "<td>" . ($entry['firstName']) . "</td>";
            echo "<td>" . ($entry['lastName']) . "</td>";
            echo "<td>" . ($entry['mail']) . "</td>";
            echo "<td>" . ($entry['zipCode']) . "</td>";
            echo "<td>";
            echo '<a href="modif.php?id=' . $entry['id'] . '">Modifier</a>';
            echo ' <a href="index.php?delete=' . $entry['id'] . '">Supprimer</a>';
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <?php
    if (isset($_POST['addUser'])) {
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $mail = $_POST['mail'];
        $zipCode = $_POST['zipCode'];

        try {
            $db -> beginTransaction(); // on active la vérification 
            $insertUser = $db->prepare("INSERT INTO user (firstName, lastName, mail, zipCode) VALUES (:firstName, :lastName, :mail, :zipCode)"); // on prépare notre requête 
            
            $insertUser -> bindParam(':firstName', $firstName);
            $insertUser -> bindParam(':lastName', $lastName);
            $insertUser -> bindParam(':mail', $mail);
            $insertUser -> bindParam(':zipCode', $zipCode);

            $insertUser -> execute(); // on exécute la requête

            $db->commit(); // tout s'est bien passé ? alors on valide la transaction
            header("Location: index.php");

        } catch (Exception $e) { // message d’erreur à afficher si une exception a été soulevée
            $db->rollBack(); // permet de restaurer la table à sa valeur initiale si une exception est soulevée
            echo $e->getMessage(); // pour afficher le message d'erreur 
        }
    }

    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        try {
            $db -> beginTransaction(); // on active la vérification 
            $deleteUser = $db->prepare("DELETE FROM user WHERE id = :id"); // on prépare notre requête
            
            $deleteUser -> bindParam(':id', $id);

            $deleteUser -> execute(); 

            $db->commit(); // tout s'est bien passé ? alors on valide la transaction
            header("Location: index.php");

        } catch (Exception $e) { // message d’erreur à afficher si une exception a été soulevée
            $db->rollBack(); // permet de restaurer la table à sa valeur initiale si une exception est soulevée
            echo $e->getMessage(); // pour afficher le message d'erreur
        }
    }
    ?>
</body>

</html>