<?php
session_start();

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "ecom_tp2"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['numAddresses']) && is_numeric($_POST['numAddresses'])) {
        $_SESSION['numAddresses'] = intval($_POST['numAddresses']);
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_POST['submitAddresses'])) {
        for ($i = 1; $i <= $_SESSION['numAddresses']; $i++) {
            $street = $_POST['street' . $i];
            $street_nb = $_POST['street_nb' . $i];
            $type = $_POST['type' . $i];
            $city = $_POST['city' . $i];
            $zipcode = $_POST['zipcode' . $i];

            $street = mysqli_real_escape_string($conn, $street);
            $type = mysqli_real_escape_string($conn, $type);
            $city = mysqli_real_escape_string($conn, $city);

            $sql = "INSERT INTO addresses (street, street_nb, type, city, zipcode) VALUES ('$street', $street_nb, '$type', '$city', '$zipcode')";

            if ($conn->query($sql) !== TRUE) {
                echo '<p>Erreur lors de l\'enregistrement de l\'adresse ' . $i . ' : ' . $conn->error . '</p>';
                echo '<p>Requête SQL : ' . $sql . '</p>';
            }
        }

        $conn->close();

        session_destroy();
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Formulaire d'Adresses</title>
</head>
<body>
    <div class="container">
        <?php if (!isset($_SESSION['numAddresses'])) : ?>
            <h1>Formulaire d'Adresses</h1>
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                <label for="numAddresses">Combien d'adresses avez-vous ?</label>
                <input type="number" name="numAddresses" id="numAddresses" required min="1">
                <button type="submit">Continuer</button>
            </form>
        <?php else : ?>
            <h1>Formulaire d'Adresses</h1>
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
                <?php for ($i = 1; $i <= $_SESSION['numAddresses']; $i++) : ?>
                    <h2>Adresse <?= $i ?></h2>
                    <label for="street<?= $i ?>">Street:</label>
                    <input type="text" name="street<?= $i ?>" id="street<?= $i ?>" maxlength="50" required>

                    <label for="street_nb<?= $i ?>">Street Number:</label>
                    <input type="number" name="street_nb<?= $i ?>" id="street_nb<?= $i ?>" required>

                    <label for="type<?= $i ?>">Type:</label>
                    <select name="type<?= $i ?>" id="type<?= $i ?>" required>
                        <option value="livraison">Livraison</option>
                        <option value="facturation">Facturation</option>
                        <option value="autre">Autre</option>
                    </select>

                    <label for="city<?= $i ?>">City:</label>
                    <select name="city<?= $i ?>" id="city<?= $i ?>" required>
                        <option value="Montreal">Montreal</option>
                        <option value="Laval">Laval</option>
                        <option value="Toronto">Toronto</option>
                        <option value="Quebec">Quebec</option>
                    </select>

                    <label for="zipcode<?= $i ?>">Zip Code:</label>
                    <input type="text" name="zipcode<?= $i ?>" id="zipcode<?= $i ?>" pattern="[0-9]{6}" required>
                    <hr>
                <?php endfor; ?>

                <button type="submit" name="submitAddresses">Confirmer</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
