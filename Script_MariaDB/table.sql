/* Projet d'intégration : FoodBook */
/* Anthony Lamothe - Guillaume Légaré - Gabriel Lessard - Samy Tétrault */

USE FoodBook;

DROP TABLE IF EXISTS Utilisateur;
CREATE TABLE IF NOT EXISTS Utilisateur(
  `idCompte` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(45) NOT NULL,
  `prenom` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idCompte`));

Drop Table IF EXISTS TypeRecette;
CREATE TABLE IF NOT EXISTS TypeRecette (
  `idTypeRecette` INT NOT NULL AUTO_INCREMENT,
  `nomTypeRecette` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idTypeRecette`));
  
DROP TABLE IF EXISTS Recette;
CREATE TABLE IF NOT EXISTS Recette (
  `idRecette` INT NOT NULL AUTO_INCREMENT,
  `idCompte` INT NOT NULL,
  `nomRecette` VARCHAR(45) NOT NULL,
  `publique` BIT(1) NOT NULL DEFAULT 0,
  `nbVus` INT NOT NULL DEFAULT 0,
  `dateCreation` DATE NOT NULL,
  `idTypeRecette` INT NOT NULL,
  PRIMARY KEY (`idRecette`),
  CONSTRAINT `fk_Recette_TypeRecette`
    FOREIGN KEY (`idTypeRecette`)
    REFERENCES `FoodBook`.`TypeRecette` (`idTypeRecette`),
    CONSTRAINT `fk_Recette_Utilisateur`
    FOREIGN KEY (`idCompte`)
    REFERENCES `FoodBook`.`Utilisateur` (`idCompte`));
    
DROP TABLE IF EXISTS InfoRecette;
CREATE TABLE IF NOT EXISTS InfoRecette(
  `tempsPreparation` VARCHAR(45) NOT NULL,
  `nbPortions` INT NOT NULL,
  `description` VARCHAR(200) NULL,
  `instruction` VARCHAR(450) NOT NULL,
  `Recette_idRecette` INT NOT NULL,
  PRIMARY KEY (`Recette_idRecette`),
  CONSTRAINT `fk_InfoRecette_Recette1`
    FOREIGN KEY (`Recette_idRecette`)
    REFERENCES `FoodBook`.`Recette` (`idRecette`));
    
DROP TABLE IF EXISTS Metrique;
CREATE TABLE IF NOT EXISTS Metrique(
  `idMetrique` INT NOT NULL AUTO_INCREMENT,
  `nomMetrique` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`idMetrique`));
  
DROP TABLE IF EXISTS TypeIngredient;
CREATE TABLE IF NOT EXISTS TypeIngredient(
  `idTypeIngredient` INT NOT NULL AUTO_INCREMENT,
  `nomType` VARCHAR(45) NOT NULL,
  `idMetrique` INT NOT NULL,
  PRIMARY KEY (`idTypeIngredient`),
  CONSTRAINT `fk_TypeIngredient_Metrique1`
    FOREIGN KEY (`idMetrique`)
    REFERENCES `FoodBook`.`Metrique` (`idMetrique`));
    
DROP TABLE IF EXISTS Ingredient;
CREATE TABLE IF NOT EXISTS Ingredient(
  `idIngredient` INT NOT NULL AUTO_INCREMENT,
  `nomIngredient` VARCHAR(45) NOT NULL,
  `descriptionIngredient` VARCHAR(100) NULL,
  `idTypeIngredient` INT NOT NULL,
  PRIMARY KEY (`idIngredient`),
  CONSTRAINT `fk_Ingredient_TypeIngredient1`
    FOREIGN KEY (`idTypeIngredient`)
    REFERENCES `FoodBook`.`TypeIngredient` (`idTypeIngredient`));
    
DROP TABLE IF EXISTS IngredientRecette;
CREATE TABLE IF NOT EXISTS IngredientRecette(
  `qteIngredient` INT NOT NULL,
  `Recette_idRecette` INT NOT NULL,
  `Ingredient_idIngredient` INT NOT NULL,
  PRIMARY KEY (`Recette_idRecette`, `Ingredient_idIngredient`),
  CONSTRAINT `fk_IngredientRecette_Recette1`
    FOREIGN KEY (`Recette_idRecette`)
    REFERENCES `FoodBook`.`Recette` (`idRecette`),
  CONSTRAINT `fk_IngredientRecette_Ingredient1`
    FOREIGN KEY (`Ingredient_idIngredient`)
    REFERENCES `FoodBook`.`Ingredient` (`idIngredient`));
    
DROP TABLE IF EXISTS Inventaire;
CREATE TABLE IF NOT EXISTS Inventaire(
  `qteIngredient` INT NOT NULL,
  `Utilisateur_idCompte` INT NOT NULL,
  `Ingredient_idIngredient` INT NOT NULL,
  PRIMARY KEY (`Utilisateur_idCompte`, `Ingredient_idIngredient`),
  CONSTRAINT `fk_Inventaire_Utilisateur1`
    FOREIGN KEY (`Utilisateur_idCompte`)
    REFERENCES `FoodBook`.`Utilisateur` (`idCompte`),
  CONSTRAINT `fk_Inventaire_Ingredient1`
    FOREIGN KEY (`Ingredient_idIngredient`)
    REFERENCES `FoodBook`.`Ingredient` (`idIngredient`));
    
    
DROP TABLE IF EXISTS HistoriqueRecetteVisioné;
CREATE TABLE IF NOT EXISTS HistoriqueRecetteVisioné(
  `dateConsultation` DATE NOT NULL,
  `Utilisateur_idCompte` INT NOT NULL,
  `Recette_idRecette` INT NOT NULL,
  PRIMARY KEY (`Utilisateur_idCompte`, `Recette_idRecette`),
  CONSTRAINT `fk_HistoriqueRecetteVisioné_Utilisateur1`
    FOREIGN KEY (`Utilisateur_idCompte`)
    REFERENCES `FoodBook`.`Utilisateur` (`idCompte`),
  CONSTRAINT `fk_HistoriqueRecetteVisioné_Recette1`
    FOREIGN KEY (`Recette_idRecette`)
    REFERENCES `FoodBook`.`Recette` (`idRecette`));
    
    
DROP TABLE IF EXISTS RecetteFavoris;
CREATE TABLE IF NOT EXISTS RecetteFavoris(
  `Utilisateur_idCompte` INT NOT NULL,
  `Recette_idRecette` INT NOT NULL,
  PRIMARY KEY (`Utilisateur_idCompte`, `Recette_idRecette`),
  CONSTRAINT `fk_RecetteFavoris_Utilisateur1`
    FOREIGN KEY (`Utilisateur_idCompte`)
    REFERENCES `FoodBook`.`Utilisateur` (`idCompte`),
  CONSTRAINT `fk_RecetteFavoris_Recette1`
    FOREIGN KEY (`Recette_idRecette`)
    REFERENCES `FoodBook`.`Recette` (`idRecette`));
    
DROP TABLE IF EXISTS ListeEpicerie;
CREATE TABLE IF NOT EXISTS ListeEpicerie(
  `idListeEpicerie` INT NOT NULL AUTO_INCREMENT,
  `nomListe` VARCHAR(45) NOT NULL,
  `descriptionListe` VARCHAR(45) NULL,
  `estListeBase` BIT(1) NOT NULL DEFAULT 0,
  `Utilisateur_idCompte` INT NOT NULL,
  PRIMARY KEY (`idListeEpicerie`),
  CONSTRAINT `fk_ListeEpicerie_Utilisateur1`
    FOREIGN KEY (`Utilisateur_idCompte`)
    REFERENCES `FoodBook`.`Utilisateur` (`idCompte`));
    
DROP TABLE IF EXISTS ContenueListeEpicerie;
CREATE TABLE IF NOT EXISTS ContenueListeEpicerie(
  `qteIngredient` INT NOT NULL,
  `estChecked` BIT(1) NOT NULL DEFAULT 0,
  `ListeEpicerie_idListeEpicerie` INT NOT NULL,
  `Ingredient_idIngredient` INT NOT NULL,
  PRIMARY KEY (`ListeEpicerie_idListeEpicerie`, `Ingredient_idIngredient`),
  CONSTRAINT `fk_ContenueListeEpicerie_ListeEpicerie1`
    FOREIGN KEY (`ListeEpicerie_idListeEpicerie`)
    REFERENCES `FoodBook`.`ListeEpicerie` (`idListeEpicerie`),
  CONSTRAINT `fk_ContenueListeEpicerie_Ingredient1`
    FOREIGN KEY (`Ingredient_idIngredient`)
    REFERENCES `FoodBook`.`Ingredient` (`idIngredient`));
    
DROP TABLE IF EXISTS EvaluationCommentaire;
CREATE TABLE IF NOT EXISTS EvaluationCommentaire(
  `evaluation` INT NOT NULL,
  `commentaire` VARCHAR(75) NULL,
  `Recette_idRecette` INT NOT NULL,
  `Utilisateur_idCompte` INT NOT NULL,
  PRIMARY KEY (`Recette_idRecette`, `Utilisateur_idCompte`),
  CONSTRAINT `fk_EvaluationCommentaire_Recette1`
    FOREIGN KEY (`Recette_idRecette`)
    REFERENCES `FoodBook`.`Recette` (`idRecette`),
  CONSTRAINT `fk_EvaluationCommentaire_Utilisateur1`
    FOREIGN KEY (`Utilisateur_idCompte`)
    REFERENCES `FoodBook`.`Utilisateur` (`idCompte`));
    
DROP TABLE IF EXISTS follower;
CREATE TABLE IF NOT EXISTS follower(
  `notification` BIT(1) NOT NULL DEFAULT 0,
  `Utilisateur_idCompte` INT NOT NULL,
  `Utilisateur_idCompteSuivis` INT NOT NULL,
  PRIMARY KEY (`Utilisateur_idCompte`, `Utilisateur_idCompteSuivis`),
  CONSTRAINT `fk_follower_Utilisateur1`
    FOREIGN KEY (`Utilisateur_idCompte`)
    REFERENCES `FoodBook`.`Utilisateur` (`idCompte`),
  CONSTRAINT `fk_follower_Utilisateur2`
    FOREIGN KEY (`Utilisateur_idCompteSuivis`)
    REFERENCES `FoodBook`.`Utilisateur` (`idCompte`));
    
DROP TABLE IF EXISTS Profil;
CREATE TABLE IF NOT EXISTS Profil(
  `descriptionProfil` VARCHAR(250) NOT NULL,
  `Utilisateur_idCompte` INT NOT NULL,
  PRIMARY KEY (`Utilisateur_idCompte`),
  CONSTRAINT `fk_Profil_Utilisateur1`
    FOREIGN KEY (`Utilisateur_idCompte`)
    REFERENCES `FoodBook`.`Utilisateur` (`idCompte`));
    
DROP TABLE IF EXISTS CodeBare;
CREATE TABLE IF NOT EXISTS CodeBare(
  `idCodeBare` INT NOT NULL AUTO_INCREMENT,
  `codeBarre` INT NOT NULL,
  `Ingredient_idIngredient` INT NOT NULL,
  PRIMARY KEY (`idCodeBare`, `Ingredient_idIngredient`),
  CONSTRAINT `fk_CodeBare_Ingredient1`