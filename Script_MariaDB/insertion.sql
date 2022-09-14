/* Projet d'intégration : FoodBook */
/* Anthony Lamothe - Guillaume Légaré - Gabriel Lessard - Samy Tétrault */

USE FoodBook;

/* Ajout des valeurs qui ne seras pas ajouter par une page web*/
/* TypeRecette,Metrique,TypeIngredient*/

/* Déjeuner, Dinner, Souper, Entrée, À-côtés , Dessert, Collations, Cocktails, Sauce  */
INSERT INTO TypeRecette VALUES('Déjeuner');
INSERT INTO TypeRecette VALUES('Diner');
INSERT INTO TypeRecette VALUES('Souper');
INSERT INTO TypeRecette VALUES('Entrée');
INSERT INTO TypeRecette VALUES('À-côtés');
INSERT INTO TypeRecette VALUES('Dessert');
INSERT INTO TypeRecette VALUES('Collations');
INSERT INTO TypeRecette VALUES('Cocktails');
INSERT INTO TypeRecette VALUES('Sauce');

/* Laitier, Viande, Légume, Fruit, Céréale, Noix, Épices, Pâte, Jus, Sucre, Autre  */
INSERT INTO TypeIngredient VALUES('Laitier');
INSERT INTO TypeIngredient VALUES('Viande');
INSERT INTO TypeIngredient VALUES('Légume');
INSERT INTO TypeIngredient VALUES('Fruit');
INSERT INTO TypeIngredient VALUES('Céréale');
INSERT INTO TypeIngredient VALUES('Noix');
INSERT INTO TypeIngredient VALUES('Épices');
INSERT INTO TypeIngredient VALUES('Pâte');
INSERT INTO TypeIngredient VALUES('Jus');
INSERT INTO TypeIngredient VALUES('Sucre');
INSERT INTO TypeIngredient VALUES('Oeufs et Substitus');
INSERT INTO TypeIngredient VALUES('Autre');

/*Gramme,Kilogramme,Milligramme, Litre, Millilitre */
INSERT INTO Metrique VALUES('Milligramme');
INSERT INTO Metrique VALUES('Gramme');
INSERT INTO Metrique VALUES('Kilogramme');
INSERT INTO Metrique VALUES('Millilitre');
INSERT INTO Metrique VALUES('Litre');
INSERT INTO Metrique VALUES('Livre');
