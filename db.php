<?php

/* Fichier Php qui vas contenir toute les entrées ou les sorties de la bd */

/* La function connexion vas être appeler à chaque function pour se connecter*/
/* Après l'appel de connexion il faut global $pdo; */
function Connexion()
{
    session_start();
    $host = '127.0.0.1';
    $db = 'FoodBook';
    $user = 'root';
    $pass = 'projetdirige';
    $charset = 'utf8';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    try {
        global $pdo; // Variable importante qui seras utile dans toute les fonctions 
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
    return null;
}

function AddUser($nom, $prenom, $email,$motDePasse)
{
    Connexion();
    global $pdo;
    try {
        $sqlProcedure = "CALL AjouterUtilisateur(:nom,:prenom,:email,:motDePasse)";
        $stmt = $pdo->prepare($sqlProcedure);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':motDePasse', $motDePasse, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

function UserExist($email)
{
    // 0 -> false
    // 1 -> true

    Connexion();
    global $pdo;

    $stmt = $pdo->prepare("SELECT EXISTS (SELECT nom FROM Utilisateur WHERE email = :pEmail);", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':pEmail', $email, PDO::PARAM_STR);
    $stmt->execute();

    $exist = false;

    if ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $etat = $donnee[0];
    }

    if ($etat == 1) {
        $exist = true;
    }
    $stmt->closeCursor();
    return $exist;
}

function VerifiyUser($email,$psswd){
    // 0 -> false
    // 1 -> true

    Connexion();
    global $pdo;

    $stmt = $pdo->prepare("SELECT EXISTS (SELECT nom FROM Utilisateur WHERE email = :pEmail AND motDePasse = :pPsswd);", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':pEmail', $email, PDO::PARAM_STR);
    $stmt->bindParam(':pPsswd', hash("sha512",$psswd), PDO::PARAM_STR);
    $stmt->execute();

    $exist = false;

    if ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $etat = $donnee[0];
    }

    if ($etat == 1) {
        $exist = true;
    }
    $stmt->closeCursor();
    return $exist;
}

/* Change the firstname, lastname and password by using the email as an identificator */
function ModifyUser($nom, $prenom, $email,$motDePasse)
{
    Connexion();
    global $pdo;
    try {
        $sqlProcedure = "CALL modifierUtilisateur(:nom,:prenom,:email,:motDePasse)";
        $stmt = $pdo->prepare($sqlProcedure);
        $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':motDePasse', $motDePasse, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
echo ModifyUser('Tetrault','Samy','sam-tetrault@hotmail.com','projetdirige');

?>
