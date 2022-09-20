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
CREATE PROCEDURE modifierUtilisateur (pNom VARCHAR(45), pPrenom VARCHAR(45), pEmail VARCHAR(45),pMotDePasse varchar(40))
BEGIN 
	IF(TRIM(pNom) != '' AND TRIM(pPrenom) != '' AND TRIM(pEmail) != '' AND TRIM(pMotDePasse) != '') THEN
		start TRANSACTION;
			UPDATE Utilisateur SET nom = pNom,prenom = pPrenom, motDePasse = SHA2(pMotDePasse, 512) WHERE email = pEmail;
		COMMIT;
	ELSE
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'There is missing or wrong paramters';
	END IF;
END$$