/* Projet d'intégration : FoodBook */
/* Anthony Lamothe - Gabriel Lessard - Samy Tétrault */

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

/*Insert: Frigidaire Principale,Congelateur Principale,Frigidaire secondaire,Congelateur Secondaire,Garde-Manger,Contoir,Cellier, Bar, Armoire a Pain*/
INSERT INTO Emplacement(idEmplacement, nomEmplacement, Svg) VALUES(1,'Frigdaire Principale','PrimaryFridge');
INSERT INTO Emplacement(idEmplacement, nomEmplacement, Svg) VALUES(2,'Frigdaire Secondaire','SecondaryFridge');
INSERT INTO Emplacement(idEmplacement, nomEmplacement, Svg) VALUES(3,'Congélateur Principale','PrimaryFreezer');
INSERT INTO Emplacement(idEmplacement, nomEmplacement, Svg) VALUES(4,'Congélateur Secondaire','SecondaryFreezer');
INSERT INTO Emplacement(idEmplacement, nomEmplacement, Svg) VALUES(5,'Garde-Manger','Pentry');
INSERT INTO Emplacement(idEmplacement, nomEmplacement, Svg) VALUES(6,'Cellier','Larder');
INSERT INTO Emplacement(idEmplacement, nomEmplacement, Svg) VALUES(7,'Bar','Bar');
INSERT INTO Emplacement(idEmplacement, nomEmplacement, Svg) VALUES(8,'Comptoir','Counter');
INSERT INTO Emplacement(idEmplacement, nomEmplacement, Svg) VALUES(9,'Armoire à pain','BreadCabinet');


/* Cool liltre insert in table Recipe to test the filters */
INSERT INTO Recette (idCompte, nomRecette, publique, nbVus,dateCreation,idTypeRecette) VALUES (3, 'Moms Spaghetti', 1, 650, '2022/10/19',3);
INSERT INTO Recette (idCompte, nomRecette, publique, nbVus,dateCreation,idTypeRecette) VALUES (3, 'Pizza', 1, 150, '2022/10/18',3);
INSERT INTO Recette (idCompte, nomRecette, publique, nbVus,dateCreation,idTypeRecette) VALUES (3, 'Creme de Brocolli', 1, 150, '2022/10/15',2);
INSERT INTO Recette (idCompte, nomRecette, publique, nbVus,dateCreation,idTypeRecette) VALUES (3, 'Steak Frite', 1, 1000, '2022/10/10',3);
INSERT INTO Recette (idCompte, nomRecette, publique, nbVus,dateCreation,idTypeRecette) VALUES (3, 'Lasagne', 1, 300, '2022/10/17',3);