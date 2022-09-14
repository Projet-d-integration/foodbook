/* Projet d'intégration : FoodBook */
/* Anthony Lamothe - Guillaume Légaré - Gabriel Lessard - Samy Tétrault */


/*Script de création de procédure stocké*/

USE FoodBook;

#Ajouter Utilisateur
DELIMITER $$
CREATE PROCEDURE AjouterUtilisateur (nom varchar(45), prenom varchar(45), email varchar(45), motDePasse varchar(40))
BEGIN
	SET @mdpChiffre = SHA2(motDePasse, 512);
    INSERT INTO Utilisateur(nom,prenom,email,motDePasse)
		VALUES(nom, prenom, email, @mdpChiffre);
END $$