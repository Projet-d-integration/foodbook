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
//Modifier Email
function ModifyEmail($idUser, $email)
{
    if(!UserExist($email)){
        Connexion();
        global $PDO;
        try {
            $sqlProcedure = "CALL modifierEmailUtilisateur(:idUser,:email)";
            $stmt = $PDO->prepare($sqlProcedure);
            $stmt->bindParam(':idUser', $idUser, PDO::PARAM_INT);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $stmt->closeCursor();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
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
/* Cela retourne un tableau qui contient les informations de l'utilisateur */
function AllUserInfo()
{
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM Utilisateur", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // Id compte
        array_push($rangee, $donnee[1]); // nom
        array_push($rangee, $donnee[2]); // prenom
        array_push($info,$rangee);
    }
    $stmt->closeCursor();
    return $info;
}
function User($idCompte)
{
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM Utilisateur WHERE idCompte = :idCompte", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_STR);
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
/*Retire un Utilisateur de la bd */
function DeleteUser($idCompte)
{
    try {
        Connexion();
        global $PDO;
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("DELETE FROM Utilisateur WHERE idCompte = :idCompte", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
/* Ajoute à la table une description au profil */
function AddInfoProfil($description,$idCompte)
{
    Connexion();
    global $PDO;
    try {
        $sqlProcedure = "CALL AjouterInfoProfil(:description,:idCompte)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

/* Modifie la description du profil */
function ModifyInfoProfil($description,$idCompte){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL ModifierInfoProfil(:description, :idCompte)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }    
    
}
/* Cela retourne un tableau qui contient les informations du profil */
function ProfilInfo($idCompte)
{
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM Profil WHERE Utilisateur_idCompte = :PIdCompte", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':pIdCompte', $idCompte, PDO::PARAM_STR);
    $stmt->execute();
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // Description
        array_push($rangee, $donnee[1]); // Id Compte
    }
    $stmt->closeCursor();
    return $rangee;
}
function DeleteProfil($idCompte)
{
    try {
        Connexion();
        global $PDO;
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("DELETE FROM Profil WHERE Utilisateur_idCompte = :idCompte", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

//Insertion table Metrique
function AddMetrique($pMetriqueName){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL AjouterMetrique(:pNomMetrique)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pNomMetrique', $pMetriqueName, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }    
}
//Modification de la métrique
function ModifyMetrique($pIdMetrique,$pMetriqueName){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL ModifierMetrique(:pIdMetrique, :pNomMetrique)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pIdMetrique', $pIdMetrique, PDO::PARAM_INT);
        $stmt->bindParam(':pNomMetrique', $pMetriqueName, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }    
}
//Information de la table Metrique Retourne un tableau 2d 
function MetriqueInfo()
{
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM Metrique", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // idMetrique
        array_push($rangee, $donnee[1]); // nomMetrique
        array_push($info, $rangee);
    }
    $stmt->closeCursor();
    return $info;
}
/*Retir une metrique */
function DeleteMetrique($idMetrique)
{
    try {
        Connexion();
        global $PDO;
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("DELETE FROM Metrique WHERE idMetrique = :idMetrique", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':idMetrique', $idMetrique, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
//Ajouter le type d'ingrédient
function AddTypeIngredient($pTypeName, $pIdMetrique){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL AjouterTypeIngredient(:pTypeNom, :pIdMetrique)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pTypeNom', $pTypeName, PDO::PARAM_STR);
        $stmt->bindParam(':pIdMetrique', $pIdMetrique, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }    
}

//Modifier le type Ingredient
function ModifyTypeIngredient($pIdType, $pNameType, $pIdMetrique){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL ModifierTypeIngredient(:pIdTypeIngredient, :pNameType, :pIdMetrique)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pIdTypeIngredient', $pIdType, PDO::PARAM_INT);
        $stmt->bindParam(':pNameType', $pNameType, PDO::PARAM_STR);
        $stmt->bindParam(':pIdMetrique', $pIdMetrique, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }    
}

//Information de la table TypeIngredient Retourne un tableau 2d 
function TypeIngredientInfo()
{
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM TypeIngredient", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // idTypeIngredient
        array_push($rangee, $donnee[1]); // nom Type
        array_push($rangee, $donnee[2]); // Id Metrique
        array_push($info, $rangee);
    }
    $stmt->closeCursor();
    return $info;
}
/*Retire un type ingredient */
function DeleteTypeIngredient($idTypeIngredient)
{
    try {
        Connexion();
        global $PDO;
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("DELETE FROM TypeIngredient WHERE idTypeIngredient = :idTypeIngredient", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':idTypeIngredient', $idTypeIngredient, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

//Ajout d'ingredient a la table
function AddIngredient($pNomIngredient, $pDescriptionIngredient, $pIdTypeIngredient){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL AjouterIngredient(:pNomIngredient, :pDescriptionIngredient, :pIdTypeIngredient)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pNomIngredient', $pNomIngredient, PDO::PARAM_STR);
        $stmt->bindParam(':pDescriptionIngredient', $pDescriptionIngredient, PDO::PARAM_STR);
        $stmt->bindParam(':pIdTypeIngredient', $pIdTypeIngredient, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }    
}
//Modifier Ingredient
function ModifyIngredient($pIdIngredient, $pNameIngredient, $pDescriptionIngredient, $pIdTypeIngredient){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL ModifierIngredient(:pIdIngredient, :pNameIngredient, :pDescriptionIngredient, :pIdTypeIngredient)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pIdIngredient', $pIdIngredient, PDO::PARAM_INT);
        $stmt->bindParam(':pNameIngredient', $pNameIngredient, PDO::PARAM_STR);
        $stmt->bindParam(':pDescriptionIngredient', $pDescriptionIngredient, PDO::PARAM_STR);
        $stmt->bindParam(':pIdTypeIngredient', $pIdTypeIngredient, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }    
}
//Suppression d'ingredient
function DeleteIngredient($idIngredient)
{
    try {
        Connexion();
        global $PDO;
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("DELETE FROM Ingredient WHERE idIngredient = :idIngredient", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':idIngredient', $idIngredient, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
//Affichage de tous les ingredients
function AllIngredientInfo($nomIngredient = '')
{
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    if($nomIngredient == '')
        $stmt = $PDO->prepare("SELECT * FROM Ingredient", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    else if ($nomIngredient == 'ASC')
        $stmt = $PDO->prepare("SELECT * FROM Ingredient ORDER BY nomIngredient ASC", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    else if ($nomIngredient == 'DESC')
        $stmt = $PDO->prepare("SELECT * FROM Ingredient ORDER BY nomIngredient DESC", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));    
 
    $stmt->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // idIngredient
        array_push($rangee, $donnee[1]); // nom Ingredient
        array_push($rangee, $donnee[2]); // Description
        array_push($rangee, $donnee[3]); // Id Type Ingredient
        array_push($info, $rangee);
    }
    $stmt->closeCursor();
    return $info;
}
//Affichage d'un seul ingredient
function SingleIngredientInfo($idIngredient)
{
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM Ingredient WHERE idIngredient = :pIdIngredient", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':pIdIngredient', $idIngredient, PDO::PARAM_STR);
    $stmt->execute();
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // idIngredient
        array_push($rangee, $donnee[1]); // nom Ingredient
        array_push($rangee, $donnee[2]); // Description
        array_push($rangee, $donnee[3]); // Id Type Ingredient
    }
    $stmt->closeCursor();
    return $rangee;
}
function IngredientExist($nomIngredient)
{
    // 0 -> false
    // 1 -> true

    Connexion();
    global $PDO;

    $stmt = $PDO->prepare("SELECT EXISTS (SELECT idIngredient FROM Ingredient WHERE nomIngredient = :pNomIngredient);", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':pNomIngredient', $nomIngredient, PDO::PARAM_STR);
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
//Ajouter Ingredient Inventaire
function AddIngredientInventory($pIdAccount, $pIdIngredient, $pQty, $pIdEmplacement){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL AjouterIngredientInventaire(:pIdCompte, :pIdIngredient, :pQte, :pIdEmplacement)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pIdCompte', $pIdAccount, PDO::PARAM_INT);
        $stmt->bindParam(':pIdIngredient', $pIdIngredient, PDO::PARAM_INT);
        $stmt->bindParam(':pQte', $pQty, PDO::PARAM_INT);
        $stmt->bindParam(':pIdEmplacement', $pIdEmplacement, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }    
}

//Modification d'ingrédient dans l'inventaire
function ModifyIngredientInventory($pIdAccount, $pIdIngredient, $pQty, $pIdEmplacement){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL ModifierIngredientInventaire(:pIdCompte, :pIdIngredient, :pQte, :pInventaireEmplacement)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pIdCompte', $pIdAccount, PDO::PARAM_INT);
        $stmt->bindParam(':pIdIngredient', $pIdIngredient, PDO::PARAM_INT);
        $stmt->bindParam(':pQte', $pQty, PDO::PARAM_INT);
        $stmt->bindParam(':pInventaireEmplacement', $pIdEmplacement, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }    
}   

//Ajouter quantiter ingredient inventaire
function AddQteIngredientInventory($pIdAccount, $pIdIngredient, $pQty){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL AjouterQteIngredientInventaire(:pIdAccount, :pIdIngredient, :pQty)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pIdAccount', $pIdAccount, PDO::PARAM_INT);
        $stmt->bindParam(':pTypIdIngredientpeNom', $pIdIngredient, PDO::PARAM_INT);
        $stmt->bindParam(':pQty', $pQty, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }    
}
//Reduire quantiter ingredient inventaire
function ReduceQteIngredientInventory($pIdAccount, $pIdIngredient, $pQty){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL ReduireQteIngredientInventaire(:pIdAccount, :pIdIngredient, :pQty)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pIdAccount', $pIdAccount, PDO::PARAM_INT);
        $stmt->bindParam(':pTypIdIngredientpeNom', $pIdIngredient, PDO::PARAM_INT);
        $stmt->bindParam(':pQty', $pQty, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }    
}
/*Retourne un tableau 2d qui contient les ingredient de l'inventaire du user*/
function UserInventoryInfo($idCompte)
{
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM Inventaire WHERE Utilisateur_idCompte = :idCompte", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
    $stmt->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // qteIngredient
        array_push($rangee, $donnee[1]); // id user
        array_push($rangee, $donnee[2]); // id Ingredient
        array_push($rangee, $donnee[3]); // Id emplacement
        array_push($info, $rangee);
    }
    $stmt->closeCursor();
    return $info;
}
//Suppression d'ingrédient a l'inventaire
function DeleteIngredientInventory($idCompte,$idIngredient,$idPlace)
{
    try {
        Connexion();
        global $PDO;
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("DELETE FROM Inventaire WHERE Utilisateur_idCompte = :idCompte AND Ingredient_idIngredient = :pIdIngredient AND inventaire_emplacement = :pIdPlace ", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
        $stmt->bindParam(':pIdIngredient', $idIngredient, PDO::PARAM_INT);
        $stmt->bindParam(':pIdPlace', $idPlace, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

//Ajouter Emplacement
function AddLocation($pNomEmplacement, $pSvg, $pIdCompte){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL AjouterEmplacement(:pNomEmplacement, :pSvg, :pIdCompte)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pNomEmplacement', $pNomEmplacement, PDO::PARAM_STR);
        $stmt->bindParam(':pSvg', $pSvg, PDO::PARAM_STR);
        $stmt->bindParam(':pIdCompte', $pIdCompte, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }    
}

//Modifier Emplacement
function ModifyLocation($pIdEmplacement, $pNomEmplacement, $pSvg){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL ModifierEmplacement(:pIdEmplacement, :pNomEmplacement, :pSvg)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pIdEmplacement', $pIdEmplacement, PDO::PARAM_INT);
        $stmt->bindParam(':pNomEmplacement', $pNomEmplacement, PDO::PARAM_STR);
        $stmt->bindParam(':pSvg', $pSvg, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }    
}

//Supprimer Emplacement
function DeleteLocation($pIdEmplacement){
    try {
        Connexion();
        global $PDO;
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("DELETE FROM Emplacement WHERE idEmplacement = :pIdEmplacement", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':pIdEmplacement', $pIdEmplacement, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

function InfoLocation($pIdCompte){
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM Emplacement where idCompte = :pIdCompte", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':pIdCompte', $pIdCompte, PDO::PARAM_INT);
    $stmt->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // idEmplacement
        array_push($rangee, $donnee[1]); // nom Emplacement
        array_push($rangee, $donnee[2]); // Svg
        array_push($rangee, $donnee[3]); // Id compte
        array_push($info, $rangee);
    }
    $stmt->closeCursor();
    return $info;
}

//Add a groceries list
function AddGroceriesList($pNomListeEpicerie,$pDescriptionListeEpicerie,$pEstListeBase,$pUtilisateur_IdCompte){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL AjouterListeEpicerie(:pNomListeEpicerie, :pDescriptionListeEpicerie, :pEstListeBase, :pUtilisateur_IdCompte)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pNomListeEpicerie', $pNomListeEpicerie, PDO::PARAM_STR);
        $stmt->bindParam(':pDescriptionListeEpicerie', $pDescriptionListeEpicerie, PDO::PARAM_STR);
        $stmt->bindParam(':pEstListeBase', $pEstListeBase, PDO::PARAM_BOOL);
        $stmt->bindParam(':pUtilisateur_IdCompte', $pUtilisateur_IdCompte, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }   
}
//Remove a groceries list
function DeleteGroceriesList($idListeEpicerie){
    try {
        Connexion();
        global $PDO;
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("DELETE FROM ListeEpicerie WHERE idListeEpicerie = :idListeEpicerie", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':idListeEpicerie', $idListeEpicerie, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
// Select all info of groceries list from user
function InfoGroceriesList($Utilisateur_idCompte){
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM ListeEpicerie where Utilisateur_idCompte = :Utilisateur_idCompte", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':Utilisateur_idCompte', $Utilisateur_idCompte, PDO::PARAM_INT);
    $stmt->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // idListeEpicerie
        array_push($rangee, $donnee[1]); // nom Liste
        array_push($rangee, $donnee[2]); // Description
        array_push($rangee, $donnee[3]); // Bool pour si la liste est de base
        array_push($rangee, $donnee[4]); // id Compte
        array_push($info, $rangee);
    }
    $stmt->closeCursor();
    return $info;
}
// Add Item to a groceries list
function AddItemToGroceries($pQteIngredient,$pEstChecked,$pListeEpicerie_idListeEpicerie,$pIngredient_idIngredient){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL AjouterContenueListeEpicerie(:pQteIngredient, :pEstChecked, :pListeEpicerie_idListeEpicerie, :pIngredient_idIngredient)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pQteIngredient', $pQteIngredient, PDO::PARAM_INT);
        $stmt->bindParam(':pEstChecked', $pEstChecked, PDO::PARAM_INT);
        $stmt->bindParam(':pListeEpicerie_idListeEpicerie', $pListeEpicerie_idListeEpicerie, PDO::PARAM_INT);
        $stmt->bindParam(':pIngredient_idIngredient', $pIngredient_idIngredient, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }   
}
//Modify an item from a list
function ModifyItemsGroceries($pQteIngredient,$pEstChecked,$pListeEpicerie_idListeEpicerie,$pIngredient_idIngredient){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL ModifierContenueListeEpicerie(:pQteIngredient, :pEstChecked, :pListeEpicerie_idListeEpicerie, :pIngredient_idIngredient)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pQteIngredient', $pQteIngredient, PDO::PARAM_INT);
        $stmt->bindParam(':pEstChecked', $pEstChecked, PDO::PARAM_INT);
        $stmt->bindParam(':pListeEpicerie_idListeEpicerie', $pListeEpicerie_idListeEpicerie, PDO::PARAM_INT);
        $stmt->bindParam(':pIngredient_idIngredient', $pIngredient_idIngredient, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }   
}
//Remove an item from a groceries list
function DeleteItemFromGroceriesList($ListeEpicerie_idListeEpicerie,$Ingredient_idIngredient){
    try {
        Connexion();
        global $PDO;
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("DELETE FROM ContenueListeEpicerie WHERE ListeEpicerie_idListeEpicerie = :ListeEpicerie_idListeEpicerie AND Ingredient_idIngredient = :Ingredient_idIngredient", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':ListeEpicerie_idListeEpicerie', $ListeEpicerie_idListeEpicerie, PDO::PARAM_INT);
        $stmt->bindParam(':Ingredient_idIngredient', $Ingredient_idIngredient, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
//Info item from groceries list
function InfoItemGroceriesList($ListeEpicerie_idListeEpicerie){
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM ContenueListeEpicerie WHERE ListeEpicerie_idListeEpicerie = :ListeEpicerie_idListeEpicerie", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':ListeEpicerie_idListeEpicerie', $ListeEpicerie_idListeEpicerie, PDO::PARAM_INT);
    $stmt->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // qteIngredient
        array_push($rangee, $donnee[1]); // bool estChecked
        array_push($rangee, $donnee[2]); // id liste epicerie
        array_push($rangee, $donnee[3]); // id ingredient
        array_push($info, $rangee);
    }
    $stmt->closeCursor();
    return $info;
}
//Add Recipe
function AddRecipe($pIdCompte, $pNomRecette, $pPublique, $pNbVus, $pDateCreation, $pIdTypeRecette){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL AjouterRecette(:pIdCompte, :pNomRecette, :pPublique, :pNbVus, :pDateCreation, :pIdTypeRecette)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pIdCompte', $pIdCompte, PDO::PARAM_INT);
        $stmt->bindParam(':pNomRecette', $pNomRecette, PDO::PARAM_STR);
        $stmt->bindParam(':pPublique', $pPublique, PDO::PARAM_INT);
        $stmt->bindParam(':pNbVus', $pNbVus, PDO::PARAM_INT);
        $stmt->bindParam(':pDateCreation', $pDateCreation, PDO::PARAM_STR);
        $stmt->bindParam(':pIdTypeRecette', $pIdTypeRecette, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }   
}
function LastInsertedRecipe(){
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT max(idRecette)FROM Recette", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->execute();
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $lastID = $donnee[0];
    }
    $stmt->closeCursor();
    return $lastID;
}
//Modify a Recipe
function ModifyRecipe($pIdCompte, $pIdRecette,$pNomRecette, $pPublique, $pNbVus, $pDateCreation, $pIdTypeRecette){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL ModifierRecette(:pIdCompte, :pIdRecette, :pNomRecette, :pPublique, :pNbVus, :pDateCreation, :pIdTypeRecette)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pIdCompte', $pIdCompte, PDO::PARAM_INT);
        $stmt->bindParam(':pIdRecette', $pIdRecette, PDO::PARAM_INT);
        $stmt->bindParam(':pNomRecette', $pNomRecette, PDO::PARAM_STR);
        $stmt->bindParam(':pPublique', $pPublique, PDO::PARAM_BOOL);
        $stmt->bindParam(':pNbVus', $pNbVus, PDO::PARAM_INT);
        $stmt->bindParam(':pDateCreation', $pDateCreation, PDO::PARAM_STR);
        $stmt->bindParam(':pIdTypeRecette', $pIdTypeRecette, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }    
}
function ModifyNameRecipe($pIdRecette,$pNomRecette){
    Connexion();
    global $PDO;
    try{
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("UPDATE Recette SET nomRecette = :pNomRecette WHERE idRecette = :pIdRecette", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':pIdRecette', $pIdRecette, PDO::PARAM_INT);
        $stmt->bindParam(':pNomRecette', $pNomRecette, PDO::PARAM_STR);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }    
}
//Remove Recipe
function DeleteRecipe($idRecette){
    try {
        Connexion();
        global $PDO;
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("DELETE FROM Recette WHERE idRecette = :idRecette", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':idRecette', $idRecette, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
//Show information of a Recipe, 
//Faire un autre SHowRecipe avec Le ID User
//Faire un filtre Ascending descending de Date et de NbVus
function ShowRecipe($idCompte = '', $pNbVus = '', $pDateCreation = ''){
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");
    if($pNbVus == '' && $pDateCreation == '' && $idCompte == ''){

        $stmt = $PDO->prepare("SELECT * FROM Recette", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    }
    else if($pNbVus == 'ASC' && $idCompte == ''){
        $stmt = $PDO->prepare("SELECT * FROM Recette ORDER BY nbVus ASC", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    }
    else if($pNbVus == 'DESC' && $idCompte == ''){
        $stmt = $PDO->prepare("SELECT * FROM Recette ORDER BY nbVus DESC", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    }
    else if($pDateCreation == 'ASC' && $idCompte == ''){
        $stmt = $PDO->prepare("SELECT * FROM Recette ORDER BY dateCreation ASC", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    }
    else if($pDateCreation == 'DESC' && $idCompte == ''){
        $stmt = $PDO->prepare("SELECT * FROM Recette ORDER BY dateCreation DESC", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    }
    else if($pNbVus == '' && $pDateCreation == ''){

        $stmt = $PDO->prepare("SELECT * FROM Recette WHERE idCompte = :idCompte", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    }
    else if($pNbVus == 'ASC'){
        $stmt = $PDO->prepare("SELECT * FROM Recette WHERE idCompte = :idCompte ORDER BY nbVus ASC", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    }
    else if($pNbVus == 'DESC'){
        $stmt = $PDO->prepare("SELECT * FROM Recette WHERE idCompte = :idCompte ORDER BY nbVus DESC", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    }
    else if($pDateCreation == 'ASC'){
        $stmt = $PDO->prepare("SELECT * FROM Recette WHERE idCompte = :idCompte ORDER BY dateCreation ASC", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    }
    else if($pDateCreation == 'DESC'){
        $stmt = $PDO->prepare("SELECT * FROM Recette WHERE idCompte = :idCompte ORDER BY dateCreation DESC", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    }
    
    $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
    $stmt->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // idRecette
        array_push($rangee, $donnee[1]); // idCompte
        array_push($rangee, $donnee[2]); // nomRecette
        array_push($rangee, $donnee[3]); // publique
        array_push($rangee, $donnee[4]); // nbVus
        array_push($rangee, $donnee[5]); // DateCreation
        array_push($rangee, $donnee[6]); // idTypeRecette
        array_push($info, $rangee);
        }
        $stmt->closeCursor();
        return $info;
}
function ShowSingleRecipe($idRecipe){
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");
    $stmt = $PDO->prepare("SELECT * FROM Recette WHERE idRecette = :idRecipe", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':idRecipe', $idRecipe, PDO::PARAM_INT);
    $stmt->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // idRecette
        array_push($rangee, $donnee[1]); // idCompte
        array_push($rangee, $donnee[2]); // nomRecette
        array_push($rangee, $donnee[3]); // publique
        array_push($rangee, $donnee[4]); // nbVus
        array_push($rangee, $donnee[5]); // DateCreation
        array_push($rangee, $donnee[6]); // idTypeRecette
        array_push($info, $rangee);
        }
        $stmt->closeCursor();
        return $info;
}
//Add Information on a Recipe
function AddInfoRecipe($pRecette_idRecette, $pImage, $pVideo,$pTempsPreparation,$pNbPortions){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL AjouterInfoRecette(:pRecette_idRecette, :pImage, :pVideo, :pTempsPreparation, :pNbPortions)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pRecette_idRecette', $pRecette_idRecette, PDO::PARAM_INT);
        $stmt->bindParam(':pImage', $pImage, PDO::PARAM_STR);
        $stmt->bindParam(':pVideo', $pVideo, PDO::PARAM_STR);
        $stmt->bindParam(':pTempsPreparation', $pTempsPreparation, PDO::PARAM_STR);
        $stmt->bindParam(':pNbPortions', $pNbPortions, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }   
}
//Modify an inforamtion about a recipe
function ModifyInfoRecipe($pTempsPreparation, $pNbPortions, $pDescription, $pInstruction, $pRecette_idRecette ,$pImage, $pVideo){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL ModifierInfoRecette(:pTempsPreparation, :pNbPortions, :pDescription, :pInstruction, :pRecette_idRecette, :pImage, :pVideo)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pTempsPreparation', $pTempsPreparation, PDO::PARAM_INT);
        $stmt->bindParam(':pNbPortions', $pNbPortions, PDO::PARAM_INT);
        $stmt->bindParam(':pDescription', $pDescription, PDO::PARAM_STR);
        $stmt->bindParam(':pInstruction', $pInstruction, PDO::PARAM_STR);
        $stmt->bindParam(':pRecette_idRecette', $pRecette_idRecette, PDO::PARAM_INT);
        $stmt->bindParam(':pImage', $pImage, PDO::PARAM_STR);
        $stmt->bindParam(':pVideo', $pVideo, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }   
}
function ModifyImageInfoRecipe($pIdRecette,$pImage){
    Connexion();
    global $PDO;
    try{
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("UPDATE InfoRecette SET image = :pImage WHERE  Recette_idRecette = :pIdRecette", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':pIdRecette', $pIdRecette, PDO::PARAM_INT);
        $stmt->bindParam(':pImage', $pImage, PDO::PARAM_STR);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }    
}
function ModifyDescriptionInfoRecipe($pIdRecette,$pDescription){
    Connexion();
    global $PDO;
    try{
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("UPDATE InfoRecette SET description = :pDescription WHERE  Recette_idRecette = :pIdRecette", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':pIdRecette', $pIdRecette, PDO::PARAM_INT);
        $stmt->bindParam(':pDescription', $pDescription, PDO::PARAM_STR);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }    
}
//Remove an information about a recipe
function DeleteInfoRecipe($pRecette_idRecette){
    try {
        Connexion();
        global $PDO;
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("DELETE FROM InfoRecette WHERE Recette_idRecette = :pRecette_idRecette", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':pRecette_idRecette', $pRecette_idRecette, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
function InfoRecipe(){
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");
    $stmt = $PDO->prepare("SELECT * FROM InfoRecette", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // TempsPreparation
        array_push($rangee, $donnee[1]); // nbPortions
        array_push($rangee, $donnee[2]); // descsription
        array_push($rangee, $donnee[3]); // instruction
        array_push($rangee, $donnee[4]); // Recette_idRecette
        array_push($rangee, $donnee[5]); // Image
        array_push($rangee, $donnee[6]); // Video
        array_push($info, $rangee);
    }
    $stmt->closeCursor();
    return $info;
}
//Show info about a recipe with a user id
function InfoRecipeByID($Recette_idRecette){
        Connexion();
        global $PDO;
        mysqli_set_charset($PDO, "utf8mb4");
    
        $stmt = $PDO->prepare("SELECT * FROM InfoRecette WHERE Recette_idRecette = :Recette_idRecette ", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':Recette_idRecette', $Recette_idRecette, PDO::PARAM_INT);
        $stmt->execute();
        $info = [];
        while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
            $rangee = [];
            array_push($rangee, $donnee[0]); // TempsPreparation
            array_push($rangee, $donnee[1]); // nbPortions
            array_push($rangee, $donnee[2]); // descsription
            array_push($rangee, $donnee[3]); // instruction
            array_push($rangee, $donnee[4]); // Recette_idRecette
            array_push($rangee, $donnee[5]); // Image
            array_push($rangee, $donnee[6]); // Video
            array_push($info, $rangee);
        }
        $stmt->closeCursor();
        return $info;
}
//Add Type Recipe
function AddTypeRecipe($pNomTypeRecette, $pImageRecette){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL AjouterTypeRecette(:pNomTypeRecette, :pImageRecette)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pNomTypeRecette', $pNomTypeRecette, PDO::PARAM_STR);
        $stmt->bindParam(':pImageRecette', $pImageRecette, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }   
}
//Modify type Recipe
function ModifyTypeRecipe($pIdTypeRecette, $pNomTypeRecette, $pImageRecette){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL ModifierTypeRecette(:pIdTypeRecette,:pNomTypeRecette,pImageRecette)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pIdTypeRecette', $pIdTypeRecette, PDO::PARAM_INT);
        $stmt->bindParam(':pNomTypeRecette', $pNomTypeRecette, PDO::PARAM_STR);
        $stmt->bindParam(':pImageRecette', $pImageRecette, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }   
}
//Remove type Recipe
function DeleteTypeRecipe($pIdTypeRecette){
    try {
        Connexion();
        global $PDO;
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("DELETE FROM TypeRecette WHERE idTypeRecette = :pIdTypeRecette", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':pIdTypeRecette', $pIdTypeRecette, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
//Show type recipe
function InfoTypeRecipe(){
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM TypeRecette", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // IdTypeRecette
        array_push($rangee, $donnee[1]); // nomTypeRecette
        array_push($rangee, $donnee[2]); // ImageTypeRecette
        array_push($info, $rangee);
    }
    $stmt->closeCursor();
    return $info;
}
function InfoSingleTypeRecipe($idTypeRecette){
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM TypeRecette WHERE idTypeRecette = :pIdTypeRecette", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':pIdTypeRecette', $idTypeRecette, PDO::PARAM_STR);
    $stmt->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // IdTypeRecette
        array_push($rangee, $donnee[1]); // nomTypeRecette
        array_push($info, $rangee);
    }
    $stmt->closeCursor();
    return $info;
}


//Add a recipe to favorites
function AddFavorites($pUtilisateur_idCompte,$pRecette_idRecette){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL AjouterRecetteFavoris(:pUtilisateur_idCompte,:pRecette_idRecette)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pUtilisateur_idCompte', $pUtilisateur_idCompte, PDO::PARAM_INT);
        $stmt->bindParam(':pRecette_idRecette', $pRecette_idRecette, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }   
}
//Remove a favorite
function DeleteFavorites($pUtilisateur_idCompte,$pRecette_idRecette){
    try {
        Connexion();
        global $PDO;
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("DELETE FROM RecetteFavoris WHERE Utilisateur_idCompte = :pUtilisateur_idCompte, Recette_idRecette = :pRecette_idRecette", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':Utilisateur_idCompte', $pUtilisateur_idCompte, PDO::PARAM_INT);
        $stmt->bindParam(':Recette_idRecette', $pRecette_idRecette, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
//Show information of a Recipe
function InfoFavoriteRecipe($pUtilisateur_idCompte,$pRecette_idRecette){
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM RecetteFavoris WHERE Utilisateur_idCompte = :pUtilisateur_idCompte, Recette_idRecette = :pRecette_idRecette", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':pUtilisateur_idCompte', $pUtilisateur_idCompte, PDO::PARAM_INT);
        $stmt->bindParam(':pRecette_idRecette', $pRecette_idRecette, PDO::PARAM_INT);
    $stmt->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // User Id
        array_push($rangee, $donnee[1]); // Recette Id
        array_push($info, $rangee);
    }
    $stmt->closeCursor();
    return $info;
}

// Info contenue recette
function AddItemToRecipe($pQteIngredient,$Recette_idRecette,$Ingredient_idIngredient, $pMesure){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL AjouterIngredientRecette(:pQteIngredient, :Recette_idRecette, :Ingredient_idIngredient, :pMesure)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pQteIngredient', $pQteIngredient, PDO::PARAM_INT);
        $stmt->bindParam(':Recette_idRecette', $Recette_idRecette, PDO::PARAM_INT);
        $stmt->bindParam(':Ingredient_idIngredient', $Ingredient_idIngredient, PDO::PARAM_INT);
        $stmt->bindParam(':pMesure', $pMesure, PDO::PARAM_STR);          
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }   
}
/*Ajouter evaluation commentaire */
function AddCommentaryEvaluation($pEvaluation, $pCommentaire, $pRecette_idRecette, $pUtilisateur_idCompte){
    Connexion();
    global $PDO;

    try{
        $sqlProcedure = "CALL AjouterEvaluationCommentaire(:pEvaluation,:pCommentaire,:pRecette_idRecette,:pUtilisateur_idCompte)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pEvaluation', $pEvaluation, PDO::PARAM_INT);
        $stmt->bindParam(':pCommentaire', $pCommentaire, PDO::PARAM_STR);
        $stmt->bindParam(':pRecette_idRecette', $pRecette_idRecette, PDO::PARAM_INT);
        $stmt->bindParam(':pUtilisateur_idCompte', $pUtilisateur_idCompte, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch(PDOException $e){
        return $e->getMessage();
    }
}
/* Modifier Evaluation commentaire */
function ModifyCommentaryEvaluation($pEvaluation, $pCommentaire, $pRecette_idRecette, $pUtilisateur_idCompte){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL ModifierEvaluationCommentaire(:pEvaluation,:pCommentaire,:pRecette_idRecette,:pUtilisateur_idCompte)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pEvaluation', $pEvaluation, PDO::PARAM_INT);
        $stmt->bindParam(':pCommentaire', $pCommentaire, PDO::PARAM_STR);
        $stmt->bindParam(':pRecette_idRecette', $pRecette_idRecette, PDO::PARAM_INT);
        $stmt->bindParam(':pUtilisateur_idCompte', $pUtilisateur_idCompte, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }   
}

function ModifyItemsRecipe($pQteIngredient,$Recette_idRecette,$Ingredient_idIngredient){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL ModifierIngredientRecette(:pQteIngredient, :Recette_idRecette, :Ingredient_idIngredient)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pQteIngredient', $pQteIngredient, PDO::PARAM_INT);
        $stmt->bindParam(':Recette_idRecette', $Recette_idRecette, PDO::PARAM_INT);
        $stmt->bindParam(':Ingredient_idIngredient', $Ingredient_idIngredient, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }   
}

function DeleteItemFromRecipe($Recette_idRecette,$Ingredient_idIngredient){
    try {
        Connexion();
        global $PDO;
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("DELETE FROM IngredientRecette WHERE Recette_idRecette = :Recette_idRecette AND Ingredient_idIngredient = :Ingredient_idIngredient", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':Recette_idRecette', $Recette_idRecette, PDO::PARAM_INT);
        $stmt->bindParam(':Ingredient_idIngredient', $Ingredient_idIngredient, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
/* Supprimer Evaluation commentaire */
function DeleteCommentaryEvaluation($pRecette_idRecette, $pUtilisateur_idCompte){
    try {
        Connexion();
        global $PDO;
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("DELETE FROM EvaluationCommentaire WHERE Utilisateur_idCompte = :pUtilisateur_idCompte AND Recette_idRecette = :pRecette_idRecette", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':Utilisateur_idCompte', $pUtilisateur_idCompte, PDO::PARAM_INT);
        $stmt->bindParam(':Recette_idRecette', $pRecette_idRecette, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
/*Show all Evaluation commentaire*/
function ShowCommentaryEvaluation($pRecette_idRecette){
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM EvaluationCommentaire WHERE Recette_idRecette = :pRecette_idRecette", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':Recette_idRecette', $pRecette_idRecette, PDO::PARAM_INT);
    $stmt->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // eval
        array_push($rangee, $donnee[1]); // commentaire
        array_push($rangee, $donnee[2]); // id recette
        array_push($rangee, $donnee[3]); // id compte
        array_push($info, $rangee);
    }
    $stmt->closeCursor();
    return $info;
}

function InfoItemRecipe($Recette_idRecette){
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");
    $stmt = $PDO->prepare("SELECT * FROM IngredientRecette WHERE Recette_idRecette = :Recette_idRecette", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':Recette_idRecette', $Recette_idRecette, PDO::PARAM_INT);
    $stmt->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // qteIngredient
        array_push($rangee, $donnee[1]); // id Recette
        array_push($rangee, $donnee[2]); // id Ingredient
        array_push($rangee, $donnee[3]); // Mesure
        array_push($info, $rangee);
    }
    $stmt->closeCursor();
    return $info;
}
/* Add follower*/
function AddFollower($pNotification, $pUtilisateur_idCompte, $pUtilisateur_idCompteSuivis){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL AjouterFollower(:pNotification,:pUtilisateur_idCompte,:pUtilisateur_idCompteSuivis)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pNotification', $pNotification, PDO::PARAM_BOOL);
        $stmt->bindParam(':pUtilisateur_idCompte', $pUtilisateur_idCompte, PDO::PARAM_INT);
        $stmt->bindParam(':pUtilisateur_idCompteSuivis', $pUtilisateur_idCompteSuivis, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch(PDOException $e){
        return $e->getMessage();
    }
}
/* Delete follower */ /* ***Pas sur d'avoir besoin du idSuivisCompte*/
function DeleteFollower($pUtilisateur_idCompte, $pUtilisateur_idCompteSuivis){
    try {
        Connexion();
        global $PDO;
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("DELETE FROM follower WHERE Utilisateur_idCompte = :pUtilisateur_idCompte AND Utilisateur_idCompteSuivis = :pUtilisateur_idCompteSuivis", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':Utilisateur_idCompte', $pUtilisateur_idCompte, PDO::PARAM_INT);
        $stmt->bindParam(':Utilisateur_idCompteSuivis', $pUtilisateur_idCompteSuivis, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
/*Show all follower*/
function ShowFollower($pUtilisateur_idCompte){
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM follower WHERE Utilisateur_idCompte = :pUtilisateur_idCompte", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':Utilisateur_idCompte', $pUtilisateur_idCompte, PDO::PARAM_INT);
    $stmt->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // Notification
        array_push($rangee, $donnee[1]); // userID
        array_push($rangee, $donnee[2]); // userFollower Id
        array_push($info, $rangee);
    }
    $stmt->closeCursor();
    return $info;
}

// Instruction
function AddInstruction($Recette_idRecette,$instruction){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL AjouterInstruction(:Recette_idRecette, :instruction)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':Recette_idRecette', $Recette_idRecette, PDO::PARAM_INT);
        $stmt->bindParam(':instruction', $instruction, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }   
}

function ModifyInstruction($instruction,$idInstruction){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL ModifierInstruction(:instruction, :idInstruction)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':instruction', $instruction, PDO::PARAM_STR);
        $stmt->bindParam(':idInstruction', $idInstruction, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }   
}

function DeleteInstruction($idInstruction){
    try {
        Connexion();
        global $PDO;
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("DELETE FROM InstructionRecette WHERE idInstruction = :idInstruction", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':idInstruction', $idInstruction, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
/* add historique visionner*/
function AddWatchingRecipeHistory($pDateConsultation, $pUtilisateur_idCompte, $pRecette_idRecette){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL AjouterHistoriqueRecetteVisioner(:pDateConsultation,:pUtilisateur_idCompte,:pRecette_idRecette)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pDateConsultation', $pDateConsultation, PDO::PARAM_STR);
        $stmt->bindParam(':pUtilisateur_idCompte', $pUtilisateur_idCompte, PDO::PARAM_INT);
        $stmt->bindParam(':pRecette_idRecette', $pRecette_idRecette, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch(PDOException $e){
        return $e->getMessage();
    }
}

/*Delete historique visionner*/
function DeleteWatchingRecipeHistory($pUtilisateur_idCompte, $pRecette_idRecette){
    try {
        Connexion();
        global $PDO;
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("DELETE FROM HistoriqueRecetteVisioné WHERE Utilisateur_idCompte = :pUtilisateur_idCompte AND Recette_idRecette = :pRecette_idRecette", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':Utilisateur_idCompte', $pUtilisateur_idCompte, PDO::PARAM_INT);
        $stmt->bindParam(':Recette_idRecette', $pRecette_idRecette, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

function InfoInstruction($Recette_idRecette){
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");
    $stmt = $PDO->prepare("SELECT * FROM InstructionRecette WHERE Recette_idRecette = :Recette_idRecette", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':Recette_idRecette', $Recette_idRecette, PDO::PARAM_INT);
    $stmt->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // idInstruction
        array_push($rangee, $donnee[1]); // id recette
        array_push($rangee, $donnee[2]); // instruction
        array_push($info, $rangee);
    }
    $stmt->closeCursor();
    return $info;
}
/*Show all historique visionner*/
function ShowWatchingRecipeHistory($pUtilisateur_idCompte){
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM HistoriqueRecetteVisioné  WHERE Utilisateur_idCompte = :pUtilisateur_idCompte", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':Utilisateur_idCompte', $pUtilisateur_idCompte, PDO::PARAM_INT);
    $stmt->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // Date Consultation
        array_push($rangee, $donnee[1]); // User id account
        array_push($rangee, $donnee[2]); // Recipe ID
        array_push($info, $rangee);
    }
    $stmt->closeCursor();
    return $info;
}


/*add Administrateur */
function AddAdmin($pidCompte, $pTitre){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL AjouterAdministrateur(:pidCompte,:pTitre)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pidCompte', $pidCompte, PDO::PARAM_INT);
        $stmt->bindParam(':pTitre', $pTitre, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->closeCursor();
    } catch(PDOException $e){
        return $e->getMessage();
    }
}
/* Modifier administrateur */
function ModifyAdmin($pidCompte, $pTitre){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL ModifierAdministrateur(:pidCompte,:pTitre)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pidCompte', $pidCompte, PDO::PARAM_INT);
        $stmt->bindParam(':pTitre', $pTitre, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->closeCursor();
    } catch(PDOException $e){
        return $e->getMessage();
    }
}

/* SUpprimer admin */
function DeleteAdmin($pidCompte, $pTitre){
    try {
        Connexion();
        global $PDO;
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("DELETE FROM Administrateur WHERE idCompte = :pidCompte AND Titre = :pTitre", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':idCompte', $pidCompte, PDO::PARAM_INT);
        $stmt->bindParam(':Titre', $pTitre, PDO::PARAM_STR);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

/* Show all admin */
function ShowAdmin($pidCompte){
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM Administrateur  WHERE idCompte = :pidCompte", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':idCompte', $pidCompte, PDO::PARAM_INT);
    $stmt->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // idCompte
        array_push($rangee, $donnee[1]); // Admin title
        array_push($info, $rangee);
    }
    $stmt->closeCursor();
    return $info;
}

/*Show Recipe made by a specific user*/ 
function UserRecipe($pidCompte){
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT nomRecette FROM Recette  WHERE idCompte = :pidCompte", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':idCompte', $pidCompte, PDO::PARAM_INT);
    $stmt->execute();
    $stmt2 = $PDO->prepare("SELECT nom,prenom FROM Utilisateur WHERE idCompte = :pidCompte", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt2->bindParam(':idCompte', $pidCompte, PDO::PARAM_INT);
    $stmt2->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // 
        array_push($info, $rangee);
    }
    $stmt->closeCursor();
    return $info;
} 
?>
