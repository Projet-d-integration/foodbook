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
function AllIngredientInfo()
{
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM Ingredient", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
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
        $sqlProcedure = "CALL ModifierIngredientInventaire(:pIdAccount, :pIdIngredient, :pQty, :pIdEmplacement)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pIdAccount', $pIdAccount, PDO::PARAM_INT);
        $stmt->bindParam(':pTypIdIngredientpeNom', $pIdIngredient, PDO::PARAM_INT);
        $stmt->bindParam(':pQty', $pQty, PDO::PARAM_INT);
        $stmt->bindParam(':pIdEmplacement', $pIdEmplacement, PDO::PARAM_INT);
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
function DeleteIngredientInventory($idCompte,$idIngredient)
{
    try {
        Connexion();
        global $PDO;
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("DELETE FROM Inventaire WHERE Utilisateur_idCompte = :idCompte AND Ingredient_idIngredient = :pIdIngredient", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
        $stmt->bindParam(':pIdIngredient', $idIngredient, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

//Ajouter Emplacement
function AddPlace($pIdEmplacement, $pNomEmplacement, $pSvg){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL AjouterEmplacement(:pIdEmplacement, :pNomEmplacement, :pSvg)";
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

//Modifier Emplacement
function ModifyPlace($pIdEmplacement, $pNomEmplacement, $pSvg){
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
function DeletePlace($pIdEmplacement, $pNomEmplacement ){
    try {
        Connexion();
        global $PDO;
        mysqli_set_charset($PDO, "utf8mb4");
        $stmt = $PDO->prepare("DELETE FROM Emplacement WHERE idEmplacement = :pIdEmplacement AND nomEmplacement = :pNomEmplacement", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
        $stmt->bindParam(':idEmplacement', $pIdEmplacement, PDO::PARAM_INT);
        $stmt->bindParam(':nomEmplacement', $pNomEmplacement, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}

function InfoPlace(){
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM Emplacement", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // idEmplacement
        array_push($rangee, $donnee[1]); // nom Emplacement
        array_push($rangee, $donnee[2]); // Svg
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
        $sqlProcedure = "CALL AjouterListeEpicerie(:pQteIngredient, :pEstChecked, :pListeEpicerie_idListeEpicerie, :pIngredient_idIngredient)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pQteIngredient', $pQteIngredient, PDO::PARAM_INT);
        $stmt->bindParam(':pEstChecked', $pEstChecked, PDO::PARAM_BOOL);
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
        $stmt->bindParam(':pEstChecked', $pEstChecked, PDO::PARAM_BOOL);
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
function InfoItemGroceriesList($ListeEpicerie_idListeEpicerie,$Ingredient_idIngredient){
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM ContenueListeEpicerie WHERE ListeEpicerie_idListeEpicerie = :ListeEpicerie_idListeEpicerie AND Ingredient_idIngredient = :Ingredient_idIngredient", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':ListeEpicerie_idListeEpicerie', $ListeEpicerie_idListeEpicerie, PDO::PARAM_INT);
    $stmt->bindParam(':Ingredient_idIngredient', $Ingredient_idIngredient, PDO::PARAM_INT);
    $stmt->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // qteIngredient
        array_push($rangee, $donnee[1]); // bool estChecked
        array_push($rangee, $donnee[2]); // id liste epicerie
        array_push($rangee, $donnee[3]); // id Compte
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
//Show information of a Recipe
function ShowRecipe($idRecette){
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM Recette WHERE idRecette = :idRecette", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':idRecette', $idRecette, PDO::PARAM_INT);
    $stmt->execute();
    $info = [];
    while ($donnee = $stmt->fetch(PDO::FETCH_NUM)) {
        $rangee = [];
        array_push($rangee, $donnee[0]); // idRecette
        array_push($rangee, $donnee[1]); // idCompte
        array_push($rangee, $donnee[2]); // nomRecette
        array_push($rangee, $donnee[3]); // publique
        array_push($rangee, $donnee[4]); //nbVus
        array_push($rangee, $donnee[5]); //DateCreation
        array_push($rangee, $donnee[6]); //idTypeRecette
        array_push($info, $rangee);
    }
    $stmt->closeCursor();
    return $info;
}
//Add Information on a Recipe
function AddInfoRecipe($pTempsPreparation, $pNbPortions, $pDescription, $pInstruction, $pRecette_idRecette){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL AjouterInfoRecette(:pTempsPreparation, :pNbPortions, :pDescription, :pInstruction, :pRecette_idRecette)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pTempsPreparation', $pTempsPreparation, PDO::PARAM_INT);
        $stmt->bindParam(':pNbPortions', $pNbPortions, PDO::PARAM_INT);
        $stmt->bindParam(':pDescription', $pDescription, PDO::PARAM_STR);
        $stmt->bindParam(':pInstruction', $pInstruction, PDO::PARAM_STR);
        $stmt->bindParam(':pRecette_idRecette', $pRecette_idRecette, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }   
}
//Modify an inforamtion about a recipe
function ModifyInfoRecipe($pTempsPreparation, $pNbPortions, $pDescription, $pInstruction, $pRecette_idRecette){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL ModifierInfoRecette(:pTempsPreparation, :pNbPortions, :pDescription, :pInstruction, :pRecette_idRecette)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pTempsPreparation', $pTempsPreparation, PDO::PARAM_INT);
        $stmt->bindParam(':pNbPortions', $pNbPortions, PDO::PARAM_INT);
        $stmt->bindParam(':pDescription', $pDescription, PDO::PARAM_STR);
        $stmt->bindParam(':pInstruction', $pInstruction, PDO::PARAM_STR);
        $stmt->bindParam(':pRecette_idRecette', $pRecette_idRecette, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->closeCursor();
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
//Show info about a recipe
function InfoRecipe($Recette_idRecette){
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM InfoRecette WHERE Recette_idRecette = :Recette_idRecette", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
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
        array_push($info, $rangee);
    }
    $stmt->closeCursor();
    return $info;
}
//Add Type Recipe
function AddTypeRecipe($pNomTypeRecette){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL AjouterTypeRecette(:pNomTypeRecette)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pNomTypeRecette', $pNomTypeRecette, PDO::PARAM_STR);
        $stmt->execute();
        $stmt->closeCursor();
    } catch (PDOException $e) {
        return $e->getMessage();
    }   
}
//Modify type Recipe
function ModifyTypeRecipe($pIdTypeRecette, $pNomTypeRecette){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL ModifierTypeRecette(:pIdTypeRecette,:pNomTypeRecette)";
        $stmt = $PDO->prepare($sqlProcedure);
        $stmt->bindParam(':pIdTypeRecette', $pIdTypeRecette, PDO::PARAM_INT);
        $stmt->bindParam(':pNomTypeRecette', $pNomTypeRecette, PDO::PARAM_STR);
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
function InfoTypeRecipe($idTypeRecette){
    Connexion();
    global $PDO;
    mysqli_set_charset($PDO, "utf8mb4");

    $stmt = $PDO->prepare("SELECT * FROM TypeRecette WHERE idTypeRecette = :idTypeRecette", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':idTypeRecette', $idTypeRecette, PDO::PARAM_INT);
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
        $stmt->bindParam(':pUtilisateur_idCompte', $pUtilisateur_idCompte, PDO::PARAM_INT);
        $stmt->bindParam(':pRecette_idRecette', $pRecette_idRecette, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        return $e->getMessage();
    }
}
//Show information of a Recipe
function InfoFavorites($pUtilisateur_idCompte,$pRecette_idRecette){
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

?>
