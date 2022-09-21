/* Projet d'intégration : FoodBook */
/* Anthony Lamothe - Guillaume Légaré - Gabriel Lessard - Samy Tétrault */

USE FoodBook;

/* Ajout des valeurs qui ne seras pas ajouter par une page web*/
/* TypeRecette,Metrique,TypeIngredient*/

/* Déjeuner, Dinner, Souper, Entrée, À-côtés , Dessert, Collations, Cocktails, Sauce  */
INSERT INTO TypeRecette(nomTypeRecette) VALUES('Déjeuner');
INSERT INTO TypeRecette(nomTypeRecette) VALUES('Diner');
INSERT INTO TypeRecette(nomTypeRecette) VALUES('Souper');
INSERT INTO TypeRecette(nomTypeRecette) VALUES('Entrée');
INSERT INTO TypeRecette(nomTypeRecette) VALUES('À-côtés');
INSERT INTO TypeRecette(nomTypeRecette) VALUES('Dessert');
INSERT INTO TypeRecette(nomTypeRecette) VALUES('Collations');
INSERT INTO TypeRecette(nomTypeRecette) VALUES('Cocktails');
INSERT INTO TypeRecette(nomTypeRecette) VALUES('Sauce');

/* Laitier, Viande, Légume, Fruit, Céréale, Noix, Épices, Pâte, Jus, Sucre, Autre  */
INSERT INTO TypeIngredient(nomType,idMetrique) VALUES('Laitier',4);
INSERT INTO TypeIngredient(nomType,idMetrique) VALUES('Viande',2);
INSERT INTO TypeIngredient(nomType,idMetrique) VALUES('Légume',2);
INSERT INTO TypeIngredient(nomType,idMetrique) VALUES('Fruit',2);
INSERT INTO TypeIngredient(nomType,idMetrique) VALUES('Céréale',2);
INSERT INTO TypeIngredient(nomType,idMetrique) VALUES('Noix',2);
INSERT INTO TypeIngredient(nomType,idMetrique) VALUES('Épices',2);
INSERT INTO TypeIngredient(nomType,idMetrique) VALUES('Pâte',2);
INSERT INTO TypeIngredient(nomType,idMetrique) VALUES('Jus',4);
INSERT INTO TypeIngredient(nomType,idMetrique) VALUES('Sucre',2);
INSERT INTO TypeIngredient(nomType,idMetrique) VALUES('Oeufs et Substitus',2);
INSERT INTO TypeIngredient(nomType,idMetrique) VALUES('Autre',2);

/*Gramme,Kilogramme,Milligramme, Litre, Millilitre */
INSERT INTO Metrique(nomMetrique) VALUES('Milligramme');
INSERT INTO Metrique(nomMetrique) VALUES('Gramme');
INSERT INTO Metrique(nomMetrique) VALUES('Kilogramme');
INSERT INTO Metrique(nomMetrique) VALUES('Millilitre');
INSERT INTO Metrique(nomMetrique) VALUES('Litre');
INSERT INTO Metrique(nomMetrique) VALUES('Livre');
