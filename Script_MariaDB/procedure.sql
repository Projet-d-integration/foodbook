/* Projet d'intégration : FoodBook */
/* Anthony Lamothe - Guillaume Légaré - Gabriel Lessard - Samy Tétrault */


/*Script de création de procédure stocké*/

USE FoodBook;

/*Ajouter Utilisateur*/
DELIMITER $$
CREATE PROCEDURE AjouterUtilisateur (nom varchar(45), prenom varchar(45), email varchar(45), motDePasse varchar(40))
BEGIN
	SET @mdpChiffre = SHA2(motDePasse, 512);
    INSERT INTO Utilisateur(nom,prenom,email,motDePasse)
		VALUES(nom, prenom, email, @mdpChiffre);
END $$

/* Modifie le nom, prenom et mot de passe de l'utilisateur*/
DELIMITER $$
CREATE PROCEDURE modifierUtilisateur (pNom VARCHAR(45), pPrenom VARCHAR(45), pEmail VARCHAR(45),pMotDePasse varbinary(256))
BEGIN 
	IF(TRIM(pNom) != '' AND TRIM(pPrenom) != '' AND TRIM(pEmail) != '' AND TRIM(pMotDePasse) != '') THEN
		start TRANSACTION;
			UPDATE Utilisateur SET nom = pNom,prenom = pPrenom, motDePasse = pMotDePasse WHERE email = pEmail;
		COMMIT;
	ELSE
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'There is missing or wrong paramters';
	END IF;
END$$

/*Ajoute une description au profil*/
#Ajouter Info Profil
DELIMITER $$
CREATE PROCEDURE AjouterInfoProfil (pDescription varchar(250), pIdCompte INT)
BEGIN
    INSERT INTO Profil(descriptionProfil,Utilisateur_idCompte)
		VALUES(pDescription,pIdCompte);
END $$

/*Modifie une description Profil*/
DELIMITER $$
CREATE PROCEDURE ModifierInfoProfil (pDescription varchar(250), pIdCompte INT)
BEGIN 
	IF(TRIM(pDescription) != '' AND TRIM(pIdCompte) != '') THEN
		start TRANSACTION;
			UPDATE Profil SET descriptionProfil = pDescription WHERE Utilisateur_idCompte = pIdCompte;
		COMMIT;
	ELSE
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'There is missing or wrong parameters';
	END IF;
END$$
/*Ajoute une metrique*/
DELIMITER $$
CREATE PROCEDURE AjouterMetrique (pNomMetrique varchar(30))
BEGIN
    INSERT INTO Metrique(nomMetrique)
		VALUES(pNomMetrique);
END $$

/*Modifie une metrique*/
DELIMITER $$
CREATE PROCEDURE ModifierMetrique (pIdMetrique INT,pNomMetrique varchar(30))
BEGIN 
	IF(TRIM(pIdMetrique) != '' AND TRIM(pNomMetrique) != '') THEN
		start TRANSACTION;
			UPDATE Metrique SET nomMetrique = pNomMetrique WHERE idMetrique = pIdMetrique;
		COMMIT;
	ELSE
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'There is missing or wrong parameters';
	END IF;
END$$

/*Ajoute un type ingredient*/
DELIMITER $$
CREATE PROCEDURE AjouterTypeIngredient (pNomType varchar(45), pIdMetrique INT)
BEGIN
    INSERT INTO TypeIngredient(nomType,idMetrique)
		VALUES(pNomType,pIdMetrique);
END $$

/*Modifie un type ingredient*/
DELIMITER $$
CREATE PROCEDURE ModifierTypeIngredient (pIdTypeIngredient INT,pNomType varchar(45), pIdMetrique INT)
BEGIN 
	IF(TRIM(pIdTypeIngredient) != '' AND TRIM(pNomType) != '' AND TRIM(pIdMetrique) != '') THEN
		start TRANSACTION;
			UPDATE TypeIngredient SET nomType = pNomType, idMetrique = pIdMetrique  WHERE idTypeIngredient = pIdTypeIngredient;
		COMMIT;
	ELSE
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'There is missing or wrong parameters';
	END IF;
END$$

/*Ajouter un ingredient*/
DELIMITER $$
CREATE PROCEDURE AjouterIngredient (pNomIngredient varchar(45),pDescriptionIngredient varchar(100),pIdTypeIngredient INT)
BEGIN
    INSERT INTO Ingredient(nomIngredient,descriptionIngredient,idTypeIngredient)
		VALUES(pNomIngredient,pDescriptionIngredient,pIdTypeIngredient);
END $$
/*Modifier un Ingredient*/
DELIMITER $$
CREATE PROCEDURE ModifierIngredient (pIdIngredient INT,pNomIngredient varchar(45),pDescriptionIngredient varchar(100),pIdTypeIngredient INT)
BEGIN 
	IF(TRIM(pIdIngredient) != '' AND TRIM(pNomIngredient) != '' AND TRIM(pDescriptionIngredient) != '' AND TRIM(pIdTypeIngredient) != '') THEN
		start TRANSACTION;
			UPDATE Ingredient SET nomIngredient = pNomIngredient, descriptionIngredient = pDescriptionIngredient, idTypeIngredient = pIdTypeIngredient WHERE idIngredient = pIdIngredient;
		COMMIT;
	ELSE
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'There is missing or wrong parameters';
	END IF;
END$$

/*Ajoute un ingredient à l'inventaire*/
DELIMITER $$
CREATE PROCEDURE AjouterIngredientInventaire (pIdCompte INT, pIdIngredient INT, pQte INT, pIdEmplacement INT)
BEGIN
    INSERT INTO Inventaire(qteIngredient,Utilisateur_idCompte,Ingredient_idIngredient, inventaire_emplacement)
		VALUES(pQte,pIdCompte,pIdIngredient, pIdEmplacement);
END $$
/*Modifie la qte d'un ingredient dans l'inventaire*/
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ModifierIngredientInventaire`(pIdCompte INT, pIdIngredient INT, pQte INT, pInventaireEmplacement INT)
BEGIN 
	IF(TRIM(pIdCompte) != '' AND TRIM(pIdIngredient) != '' AND TRIM(pQte) != '' AND TRIM(pInventaireEmplacement) != '' ) THEN
		start TRANSACTION;
			UPDATE Inventaire SET qteIngredient = pQte WHERE  Utilisateur_idCompte = pIdCompte AND Ingredient_idIngredient = pIdIngredient AND inventaire_emplacement = pInventaireEmplacement;
		COMMIT;
	ELSE
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'There is missing or wrong parameters';
	END IF;
END
/*Augmenter de 1 la qte de l'ingredient*/
DELIMITER $$
CREATE PROCEDURE AjouterQteIngredientInventaire (pIdCompte INT, pIdIngredient INT, pQte INT)
BEGIN 
	IF(TRIM(pIdCompte) != '' AND TRIM(pIdIngredient) != '' AND TRIM(pQte) != '' ) THEN
		start TRANSACTION;
			SET @qte = pQte + 1;
			UPDATE Inventaire SET qteIngredient = pQte  WHERE  Utilisateur_idCompte = pIdCompte AND pIdIngredient = pIdTypeIngredient;
		COMMIT;
	ELSE
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'There is missing or wrong parameters';
	END IF;
END$$
/*Reduis de 1 la qte de l'ingredient*/
DELIMITER $$
CREATE PROCEDURE ReduireQteIngredientInventaire (pIdCompte INT, pIdIngredient INT, pQte INT)
BEGIN 
	IF(TRIM(pIdCompte) != '' AND TRIM(pIdIngredient) != '' AND TRIM(pQte) != '' ) THEN
		start TRANSACTION;
			SET @qte = pQte - 1;
			UPDATE Inventaire SET qteIngredient = pQte WHERE  Utilisateur_idCompte = pIdCompte AND pIdIngredient = pIdTypeIngredient;
		COMMIT;
	ELSE
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'There is missing or wrong parameters';
	END IF;
END$$
/*Modifie le email du user*/
DELIMITER $$
CREATE PROCEDURE modifierEmailUtilisateur (pIdCompte INT,pEmail VARCHAR(45))
BEGIN 
	IF(TRIM(pIdCompte) != '' AND TRIM(pEmail) != '') THEN
		start TRANSACTION;
			UPDATE Utilisateur SET email = pEmail WHERE idCompte = pIdCompte;
		COMMIT;
	ELSE
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'There is missing or wrong parameters';
	END IF;
END$$

DELIMITER $$
CREATE PROCEDURE AjouterEmplacement (pIdEmplacement INT,pNomEmplacement varchar(45), pSvg VARCHAR(40))
BEGIN
    INSERT INTO Emplacement(idEmplacement, nomEmplacement, Svg)
		VALUES(pIdEmplacement, pNomEmplacement, pSvg);
END $$

DELIMITER $$
CREATE PROCEDURE ModifierPlacement (pIdEmplacement INT,pNomEmplacement varchar(45), pSvg VARCHAR(40))
BEGIN 
	IF(TRIM(pIdEmplacement) != '' AND TRIM(pNomEmplacement) != '' AND TRIM(pSvg) != '') THEN
		start TRANSACTION;
			UPDATE Emplacement SET nomEmplacement = pNomEmplacement, Svg = pSvg WHERE idEmplacement = pIdEmplacement;
		COMMIT;
	ELSE
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'There is missing or wrong parameters';
	END IF;
END$$
