/* Projet d'intégration : FoodBook */
/* Anthony Lamothe - Guillaume Légaré - Gabriel Lessard - Samy Tétrault */


/*Script de création de trigger*/

USE FoodBook;

#SupprimerCompte
DELIMITER $$
CREATE TRIGGER `deleteUtilisateurOnCascade` AFTER DELETE ON `Utilisateur` 
FOR EACH ROW 
	SET @idCompte = old.idCompte;
    #DeleteOnCascade
    DELETE FROM Recette WHERE Utilisateur_idCompte = @idCompte;
    DELETE FROM Inventaire WHERE Utilisateur_idCompte = @idCompte;
    DELETE FROM HistoriqueRecetteVisioné WHERE Utilisateur_idCompte = @idCompte;
    DELETE FROM RecetteFavoris WHERE Utilisateur_idCompte = @idCompte;
    DELETE FROM ListeEpicerie WHERE Utilisateur_idCompte = @idCompte;
    DELETE FROM EvaluationCommentaire WHERE Utilisateur_idCompte = @idCompte;
    DELETE FROM follower WHERE Utilisateur_idCompte = @idCompte;
    DELETE FROM Profil WHERE Utilisateur_idCompte = @idCompte;
END $$