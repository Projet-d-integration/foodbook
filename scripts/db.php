<?php

/* Fichier Php qui vas contenir toute les entrées ou les sorties de la bd */

/* La function connexion vas être appeler à chaque function pour se connecter*/
/* Après l'appel de connexion il faut global $PDO; */
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
        global $PDO; // Variable importante qui seras utile dans toute les fonctions 
        $PDO = new PDO($dsn, $user, $pass, $options);
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
    return null;
}

function AddUser($nom, $prenom, $email,$motDePasse)
{
    Connexion();
    global $PDO;
    try {
        $sqlProcedure = "CALL AjouterUtilisateur(:nom,:prenom,:email,:motDePasse)";
        $stmt = $PDO->prepare($sqlProcedure);
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
    global $PDO;

    $stmt = $PDO->prepare("SELECT EXISTS (SELECT nom FROM Utilisateur WHERE email = :pEmail);", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
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

Function VerifyUser($email,$psswd){
    // 0 -> false
    // 1 -> true

    Connexion();
    global $PDO;

    $stmt = $PDO->prepare("SELECT EXISTS (SELECT nom FROM Utilisateur WHERE email = :pEmail AND motDePasse = :pPsswd);", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
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
    global $PDO;
    try {
        $sqlProcedure = "CALL modifierUtilisateur(:nom,:prenom,:email,:motDePasse)";
        $stmt = $PDO->prepare($sqlProcedure);
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

/* Cela retourne un tableau qui contient les informations de l'utilisateur */
function UserInfo($email)
{
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM Utilisateur WHERE email = :pEmail", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':pEmail', $email, PDO::PARAM_STR);
    $stmt->execute();
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // Id compte
        array_push($rangee, $donnee[1]); // nom
        array_push($rangee, $donnee[2]); // prenom
        array_push($rangee, $donnee[3]); // email
        array_push($rangee, $donnee[4]); // mot de passe chiffrer (hash("sha512",$psswd))
    }
    $stmt->closeCursor();
    return $rangee;
}

/* Liste a faire sur Workbench et Php */
/*
    1- Insertion,Mofication et Select de la table profil
    2- Insertion,Mofication et Select de la table ingredient
    3- Insertion,Mofication et Select de la table inventaire
    4- Creer une fonction pour ajouter et retirer un ingredient (+1 et -1)
    5- Retirer un ingredient de mon inventaire
*/
?>
