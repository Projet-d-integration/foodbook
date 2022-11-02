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
CREATE DEFINER=`root`@`localhost` PROCEDURE `AjouterEmplacement`(pNomEmplacement varchar(45), pSvg VARCHAR(40), pIdCompte INT)
BEGIN
    INSERT INTO Emplacement(nomEmplacement, Svg, idCompte)
		VALUES(pNomEmplacement, pSvg, pIdCompte);
END

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

/* Ajouter une liste Epicerie */
DELIMITER $$
CREATE PROCEDURE AjouterListeEpicerie (pNomListeEpicerie varchar(45), pDescriptionListeEpicerie varchar(40), pEstListeBase bit(1), pUtilisateur_IdCompte INT)
BEGIN
    INSERT INTO ListeEpicerie(nomListe, descriptionListe, estListeBase, Utilisateur_idCompte)
		VALUES(pNomListeEpicerie, pDescriptionListeEpicerie, pEstListeBase, pUtilisateur_IdCompte);
END$$ 

/* Modifier une liste Epicerie */
DELIMITER $$
CREATE PROCEDURE ModifierListeEpicerie (pIdListeEpicerie INT, pNomListeEpicerie varchar(45), pDescriptionListeEpicerie varchar(40), pEstListeBase bit(1), pUtilisateur_IdCompte INT)
BEGIN
    UPDATE ListeEpicerie SET nomListe = pNomListeEpicerie, descriptionListe = pDescriptionListeEpicerie, estListeBase = pListeBase, Utilisateur_idCompte = pUtilisateur_IdCompte WHERE idListeEpicerie = pIdListeEpicerie;
END$$ 

/* Ajouter une recette*/
DELIMITER $$
CREATE PROCEDURE AjouterRecette (pIdCompte int, pNomRecette varchar(45), pPublique bit(1), pNbVus int, pDateCreation date, pIdTypeRecette int)
BEGIN
    INSERT INTO Recette(idCompte, nomRecette, publique, nbVus, dateCreation, idTypeRecette)
		VALUES(pIdCompte, pNomRecette, pPublique, pNbVus, pDateCreation, pIdTypeRecette);
END$$

/* Modifier une recette*/
DELIMITER $$
CREATE PROCEDURE ModifierRecette (pIdCompte int,pIdRecette int, pNomRecette varchar(45), pPublique bit(1), pNbVus int, pDateCreation date, pIdTypeRecette int)
BEGIN
    UPDATE Recette SET idCompte = pIdCompte, nomRecette = pNomRecette, publique = pPublique, nbVus = pNbVus, dateCreation = pDateCreation, idTypeRecette = pIdTypeRecette WHERE idRecette = pIdRecette;
END$$ 

/* Ajouter Conetenue Liste Epicerie */
DELIMITER $$
CREATE PROCEDURE AjouterContenueListeEpicerie (pQteIngredient int, pEstChecked bit(1), pListeEpicerie_idListeEpicerie int, pIngredient_idIngredient int)
BEGIN
    INSERT INTO ContenueListeEpicerie(qteIngredient,estChecked,ListeEpicerie_idListeEpicerie,Ingredient_idIngredient)
		VALUES(pQteIngredient, pEstChecked, pListeEpicerie_idListeEpicerie, pIngredient_idIngredient);
END$$ 

/* Modifier Contenue Liste Epicerie */
DELIMITER $$
CREATE PROCEDURE ModifierContenueListeEpicerie (pQteIngredient int, pEstChecked bit(1), pListeEpicerie_idListeEpicerie int, pIngredient_idIngredient int)
BEGIN
    UPDATE ContenueListeEpicerie SET qteIngredient = pQteIngredient, estChecked = pEstChecked, Ingredient_idIngredient = pIngredient_idIngredient WHERE ListeEpicerie_idListeEpicerie = pListeEpicerie_idListeEpicerie;
END$$ 

/* Ajouter InfoRecette */
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AjouterInfoRecette`(pRecette_IdRecette int, pImage VARCHAR(650), pVideo VARCHAR(650))
BEGIN
    INSERT INTO InfoRecette(Recette_idRecette, image, video)
		VALUES(pRecette_IdRecette, pImage, pVideo);
END

/* Modifier InfoRecette */
DELIMITER $$
CREATE PROCEDURE ModifierInfoRecette (pTempsPreparation varchar(45), pNbPortions int, pDescription varchar(200), pInstruction varchar(450), pRecette_IdRecette int, pImage VARCHAR(650), pVideo VARCHAR(650))
BEGIN
    UPDATE InfoRecette SET tempsPreparation = pTempsPreparation, nbPortions = pNbPortions, description = pDescription, instruction = pInstruction, image = pImage, video = pVideo WHERE Recette_idRecette = pRecette_IdRecette;
END$$ 

/* Ajouter Recette Favoris */
DELIMITER $$
CREATE PROCEDURE AjouterRecetteFavoris (pUtilisateur_idCompte int, pRecette_idRecette int)
BEGIN
    INSERT INTO RecetteFavoris(Utilisateur_idCompte,Recette_idRecette)
		VALUES(pUtilisateur_idCompte, pRecette_idRecette);
END$$ 

/* Modifier Recette Favoris */
DELIMITER $$
CREATE PROCEDURE ModifierRecetteFavoris (pUtilisateur_idCompte int, pRecette_idRecette int)
BEGIN
    UPDATE RecetteFavoris SET Recette_idRecette = pRecette_IdRecette WHERE Utilisateur_idCompte = pUtilisateur_idCompte;
END$$

/* Ajouter TypeRecette */
DELIMITER $$
CREATE PROCEDURE AjouterTypeRecette (pNomTypeRecette varchar(45))
BEGIN
    INSERT INTO TypeRecette(nomTypeRecette)
		VALUES(pNomTypeRecette);
END$$

/* MOdifier TypeRecette */
DELIMITER $$
CREATE PROCEDURE ModifierTypeRecette(pIdTypeRecette int, pNomTypeRecette varchar(45))
BEGIN
    UPDATE TypeRecette SET nomTypeRecette = pNomTypeRecette WHERE idTypeRecette = pIdTypeRecette;
END$$ 

/* Ajouter CodeBare */
DELIMITER $$
CREATE PROCEDURE AjouterTypeRecette (pNomTypeRecette varchar(45))
BEGIN
    INSERT INTO TypeRecette(nomTypeRecette)
		VALUES(pNomTypeRecette);
END$$

/* Modifier Code Bare */
DELIMITER $$
CREATE PROCEDURE ModifierCodeBare(pIdCodeBare int, pCodeBarre int, pIngredient_idIngredient int)
BEGIN
    UPDATE CodeBare SET codeBarre = pCodeBarre, Ingredient_idIngredient = pIngredient_idIngredient WHERE idCodeBare = pIdCodeBare;
END$$ 

/*Ajouter evaluation commentaire */