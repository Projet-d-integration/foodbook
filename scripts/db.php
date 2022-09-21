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
function AddIngredient($pTypeName, $pIdMetrique){
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
//Ajouter Ingredient Inventaire
function AddIngredientInventory($pIdAccount, $pIdIngredient, $pQty){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL AjouterIngredientInventaire(:pIdAccount, :pIdIngredient, :pQty)";
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

//Modification d'ingrédient dans l'inventaire
function ModifyIngredientInventory($pIdAccount, $pIdIngredient, $pQty){
    Connexion();
    global $PDO;
    try{
        $sqlProcedure = "CALL ModifierIngredientInventaire(:pIdAccount, :pIdIngredient, :pQty)";
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

    $stmt = $PDO->prepare("SELECT * FROM Inventaire WHERE Utilisateur_idCompte = idCompte", array(PDO::ATTR_CURSOR, PDO::CURSOR_FWDONLY));
    $stmt->bindParam(':idCompte', $idCompte, PDO::PARAM_INT);
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
?>
